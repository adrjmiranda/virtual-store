<?php

namespace App\Services\Domain;

use App\Domain\CartItems\CartItem;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\CartItemInputDTO;
use App\Exceptions\CartItemCreationException;
use App\Exceptions\CartItemRemoveException;
use App\Exceptions\CartItemUpdateException;
use App\Factories\CartItemFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CartItemRepository;
use App\Repository\EventLogRepository;
use Exception;

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

  public function show(int $id): ?CartItem
  {
    return $this->repo->find($id);
  }

  public function update(int $id, CartItemInputDTO $dto, array $fields): ?CartItem
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $cartItemToUpdate = $this->repo->find($id);
      if ($cartItemToUpdate === null) {
        throw new Exception("Cart item not found", 500);
      }
      $cartItem = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($cartItem, $fields);

      if (!$updated) {
        throw new CartItemUpdateException();
      }

      $updatedCartItem = $updated ? $this->repo->find($id) : null;
      if ($updatedCartItem === null) {
        throw new CartItemUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Item do carrinho atualizado ID {$updatedCartItem->idValue()} ProductId {$updatedCartItem->productIdValue()} Price {$updatedCartItem->priceValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedCartItem;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $cartItem = $this->repo->find($id);
      if ($cartItem === null) {
        throw new Exception("Cart item not found", 500);
      }

      $deleted = $this->repo->delete($cartItem);
      if (!$deleted) {
        throw new CartItemRemoveException();
      }

      $this->eventLog->record(
        EventType::DELETE,
        "Item do carrinho atualizado ID {$cartItem->idValue()} ProductId {$cartItem->productIdValue()} Price {$cartItem->priceValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $deleted;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}