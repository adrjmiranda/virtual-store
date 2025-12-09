<?php

namespace App\Services\Domain;

use App\Domain\Orders\Order;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\OrderInputDTO;
use App\Exceptions\OrderCreationException;
use App\Exceptions\OrderRemoveException;
use App\Exceptions\OrderUpdateException;
use App\Factories\OrderFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderRepository;
use Exception;
use ReturnTypeWillChange;

class OrderService
{
  public function __construct(
    private OrderRepository $repo,
    private OrderFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(OrderInputDTO $dto): ?Order
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $order = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($order, Order::FIELDS_INSERT);

      if ($id === null) {
        throw new OrderCreationException();
      }

      $createdOrder = $this->repo->find($id);
      if ($createdOrder === null) {
        throw new OrderCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Ordem criada: ID {$order->idValue()} Usuário {$order->userIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdOrder;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?Order
  {
    return $this->repo->find($id);
  }

  public function update(int $id, OrderInputDTO $dto, array $fields): ?Order
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $orderToUpdate = $this->repo->find($id);
      if ($orderToUpdate === null) {
        throw new Exception('Order not found.', 500);
      }
      $order = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($order, $fields);

      if (!$updated) {
        throw new OrderUpdateException();
      }

      $updatedOrder = $updated ? $this->repo->find($id) : null;
      if ($updatedOrder === null) {
        throw new OrderUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Ordem atualizada: ID {$updatedOrder->idValue()} Usuário {$updatedOrder->userIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedOrder;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $order = $this->repo->find($id);
      if ($order === null) {
        throw new Exception('Order not found.', 500);
      }

      $deleted = $this->repo->delete($order);
      if (!$deleted) {
        throw new OrderRemoveException();
      }

      $this->eventLog->record(
        EventType::DELETE,
        "Ordem removida: ID {$order->idValue()} Usuário {$order->userIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $deleted;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}