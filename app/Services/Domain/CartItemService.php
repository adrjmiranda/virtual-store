<?php

namespace App\Services\Domain;

use App\Domain\CartItems\CartItem;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\CartItemInputDTO;
use App\Exceptions\CartItemCreationException;
use App\Factories\CartItemFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CartItemRepository;
use App\Repository\EventLogRepository;

class CartItemService
{
  public function __construct(
    private CartItemRepository $repo,
    private CartItemFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(CartItemInputDTO $dto): ?CartItem
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $cartItem = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($cartItem, CartItem::FIELDS_INSERT);

      if ($id === null) {
        throw new CartItemCreationException();
      }

      $createdCartItem = $this->repo->find($id);
      if ($createdCartItem === null) {
        throw new CartItemCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Item do carrinho criado ID {$createdCartItem->idValue()} ProductId {$createdCartItem->productIdValue()} Price {$createdCartItem->priceValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdCartItem;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}