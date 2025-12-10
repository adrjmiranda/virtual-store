<?php

namespace App\Services\Domain;

use App\Domain\OrderDiscounts\OrderDiscount;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\OrderDiscountInputDTO;
use App\Exceptions\OrderDiscountCreationException;
use App\Exceptions\OrderDiscountRemoveException;
use App\Exceptions\OrderDiscountUpdateException;
use App\Factories\OrderDiscountFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderDiscountRepository;
use Exception;

class OrderDiscountService
{
  public function __construct(
    private OrderDiscountRepository $repo,
    private OrderDiscountFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(OrderDiscountInputDTO $dto): ?OrderDiscount
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $orderDiscount = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($orderDiscount, OrderDiscount::FIELDS_INSERT);

      if ($id === null) {
        throw new OrderDiscountCreationException();
      }

      $createdOrderDiscount = $this->repo->find($id);
      if ($createdOrderDiscount === null) {
        throw new OrderDiscountCreationException();
      }


      $this->eventLog->record(
        EventType::CREATE,
        "Desconto de ordem criada ID {$createdOrderDiscount->idValue()} Code {$createdOrderDiscount->discountCodeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdOrderDiscount;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?OrderDiscount
  {
    return $this->repo->find($id);
  }

  public function update(int $id, OrderDiscountInputDTO $dto, array $fields): ?OrderDiscount
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $orderDiscountToUpdate = $this->repo->find($id);
      if ($orderDiscountToUpdate === null) {
        throw new Exception('Order discount not found.', 500);
      }
      $orderDiscount = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($orderDiscount, $fields);

      if (!$updated) {
        throw new OrderDiscountUpdateException();
      }

      $updatedOrderDiscount = $updated ? $this->repo->find($id) : null;
      if ($updatedOrderDiscount === null) {
        throw new OrderDiscountUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Desconto de ordem atualizado ID {$updatedOrderDiscount->idValue()} Code {$updatedOrderDiscount->discountCodeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedOrderDiscount;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $orderDiscountItem = $this->repo->find($id);
      if ($orderDiscountItem === null) {
        throw new Exception('Order discount not found.', 500);
      }

      $deleted = $this->repo->delete($orderDiscountItem);
      if (!$deleted) {
        throw new OrderDiscountRemoveException();
      }

      $this->eventLog->record(
        EventType::DELETE,
        "Desconto de ordem removida ID {$orderDiscountItem->idValue()} Code {$orderDiscountItem->discountCodeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $deleted;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}