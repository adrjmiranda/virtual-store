<?php

namespace App\Services\Domain;

use App\Domain\OrderDiscounts\OrderDiscount;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\OrderDiscountInputDTO;
use App\Exceptions\OrderDiscountCreationException;
use App\Factories\OrderDiscountFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderDiscountRepository;

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
}