<?php

namespace App\Services\Domain;

use App\Domain\OrderItems\OrderItem;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\OrderItemInputDTO;
use App\Exceptions\OrderItemCreationException;
use App\Exceptions\OrderItemUpdateException;
use App\Factories\OrderItemFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderItemRepository;
use Exception;

class OrderItemService
{
  public function __construct(
    private OrderItemRepository $repo,
    private OrderItemFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(OrderItemInputDTO $dto): ?OrderItem
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $orderItem = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($orderItem, OrderItem::FIELDS_INSERT);

      if ($id === null) {
        throw new OrderItemCreationException();
      }

      $createdOrderItem = $this->repo->find($id);
      if ($createdOrderItem === null) {
        throw new OrderItemCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Item de ordem criada ID {$createdOrderItem->idValue()} OrderID {$createdOrderItem->orderIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdOrderItem;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?OrderItem
  {
    return $this->repo->find($id);
  }

  public function update(int $id, OrderItemInputDTO $dto, array $fields): ?OrderItem
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $orderItemToUpdate = $this->repo->find($id);
      if ($orderItemToUpdate === null) {
        throw new Exception('Order item not found.', 500);
      }
      $oderItem = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($oderItem, $fields);

      if (!$updated) {
        throw new OrderItemUpdateException();
      }

      $updatedOrderItem = $updated ? $this->repo->find($id) : null;
      if ($updatedOrderItem === null) {
        throw new OrderItemUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Item de ordem atualizado ID {$updatedOrderItem->idValue()} OrderID {$updatedOrderItem->orderIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedOrderItem;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}