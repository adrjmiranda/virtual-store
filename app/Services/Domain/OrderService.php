<?php

namespace App\Services\Domain;

use App\Domain\Orders\Order;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\OrderInputDTO;
use App\Exceptions\OrderCreationException;
use App\Factories\OrderFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderRepository;

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
        "Order criada: ID {$order->idValue()} Usuário {$order->userIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdOrder;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}