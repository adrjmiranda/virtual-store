<?php

namespace App\Services\Domain;

use App\Domain\Carts\Cart;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\CartInputDTO;
use App\Exceptions\CartCreationException;
use App\Exceptions\CartUpdateException;
use App\Factories\CartFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CartRepository;
use App\Repository\EventLogRepository;
use Exception;

class CartService
{
  public function __construct(
    private CartRepository $repo,
    private CartFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(CartInputDTO $dto): ?Cart
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $cart = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($cart, Cart::FIELDS_INSERT);

      if ($id === null) {
        throw new CartCreationException();
      }

      $createdCart = $this->repo->find($id);
      if ($createdCart === null) {
        throw new CartCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Carrinho criado ID {$createdCart->idValue()} para o usuário de ID {$createdCart->userIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdCart;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?Cart
  {
    return $this->repo->find($id);
  }

  public function update(int $id, CartInputDTO $dto, array $fields): ?Cart
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $cartToUpdate = $this->repo->find($id);
      if ($cartToUpdate === null) {
        throw new Exception('Cart not found.', 500);
      }
      $cart = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($cart, $fields);

      if (!$updated) {
        throw new CartUpdateException();
      }

      $updatedCart = $updated ? $this->repo->find($id) : null;
      if ($updatedCart === null) {
        throw new CartUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Carrinho atualizado ID {$updatedCart->idValue()} para o usuário de ID {$updatedCart->userIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedCart;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}