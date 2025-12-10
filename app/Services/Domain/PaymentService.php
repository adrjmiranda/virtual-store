<?php

namespace App\Services\Domain;

use App\Domain\Payments\Payment;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\PaymentInputDTO;
use App\Exceptions\PaymentCreationException;
use App\Exceptions\PaymentUpdateException;
use App\Factories\PaymentFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\PaymentRepository;
use Exception;

class PaymentService
{
  public function __construct(
    private PaymentRepository $repo,
    private PaymentFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(PaymentInputDTO $dto): ?Payment
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $payment = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($payment, Payment::FIELDS_INSERT);

      if ($id === null) {
        throw new PaymentCreationException();
      }

      $createdPayment = $this->repo->find($id);
      if ($createdPayment === null) {
        throw new PaymentCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Pagamento criado ID: {$createdPayment->idValue()} Método {$createdPayment->methodValue()} Amount {$createdPayment->amountValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdPayment;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?Payment
  {
    return $this->repo->find($id);
  }

  public function update(int $id, PaymentInputDTO $dto, array $fields): ?Payment
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $paymentToUpdate = $this->repo->find($id);
      if ($paymentToUpdate === null) {
        throw new Exception("Payment not found", 500);
      }
      $payment = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($payment, $fields);

      if (!$updated) {
        throw new PaymentUpdateException();
      }

      $updatedPayment = $updated ? $this->repo->find($id) : null;
      if ($updatedPayment === null) {
        throw new PaymentUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Pagamento atualizado ID: {$updatedPayment->idValue()} Método {$updatedPayment->methodValue()} Amount {$updatedPayment->amountValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedPayment;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}