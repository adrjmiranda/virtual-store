<?php

namespace App\Factories;

use App\Domain\Payments\Amount;
use App\Domain\Payments\Method;
use App\Domain\Payments\PaidAt;
use App\Domain\Payments\Payment;
use App\Domain\Payments\Status;
use App\Domain\Payments\TransactionId;
use App\Domain\ValueObjects\Enum\PaymentStatus;
use App\Domain\ValueObjects\UserId;
use App\DTO\PaymentInputDTO;

class PaymentFactory
{
  public function fromDTO(PaymentInputDTO $dto, ?Payment $payment = null): Payment
  {
    return new Payment(
      id: $payment?->id(),
      userId: $dto->userId === null ? $payment?->userId() : new UserId($dto->userId),
      amount: $dto->amount === null ? $payment?->amount() : new Amount($dto->amount),
      method: $dto->method === null ? $payment?->method() : new Method($dto->method),
      status: $dto->status === null ? $payment?->status() : new Status(PaymentStatus::from($dto->status)),
      transactionId: $dto->transactionId === null ? $payment?->transactionId() : new TransactionId($dto->transactionId),
      paidAt: $dto->paidAt === null ? $payment?->paidAt() : new PaidAt($dto->paidAt),
      createdAt: null,
      updatedAt: null
    );
  }
}