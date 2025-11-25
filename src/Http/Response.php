<?php

namespace VirtualStore\Http;

class Response
{
  private int $status;
  private array $headers;
  private mixed $body;

  public function __construct(
    mixed $body = '',
    int $status = 200,
    array $headers = []
  ) {
    $this->body = $body;
    $this->status = $status;
    $this->headers = $headers;
  }

  public function status(int $code): self
  {
    $this->status = $code;
    return $this;
  }

  public function header(string $key, string $value): self
  {
    $this->headers[$key] = $value;
    return $this;
  }

  public function headers(array $headers): self
  {
    foreach ($headers as $key => $value) {
      $this->headers[$key] = $value;
    }
    return $this;
  }

  public static function json(array|object $data, int $status = 200): self
  {
    return new self(
      json_encode($data),
      $status,
      ['Content-Type' => 'application/json']
    );
  }

  public static function redirect(string $to, int $status = 302): self
  {
    return new self(
      '',
      $status,
      ['Location' => $to]
    );
  }

  public static function back(int $status = 302, string $fallback = '/'): self
  {
    $referer = $_SERVER['HTTP_REFERER'] ?? null;
    $target = $referer ?: $fallback;

    return new self(
      '',
      $status,
      ['Location' => $target]
    );
  }

  public function send(): void
  {
    http_response_code($this->status);

    foreach ($this->headers as $key => $value) {
      header("{$key}: $value");
    }

    echo $this->body;
  }
}