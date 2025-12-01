<?php

namespace App\Http\Message;

class Request
{
  private string $method;
  private string $uri;
  private array $headers;
  private array $query;
  private array $body;
  private array $files;
  private array $cookies;
  private array $server;
  private ?array $json;
  private string $rawBody;

  public function __construct()
  {
    $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $this->uri = strtok($_SERVER['REQUEST_URI'], '?');
    $this->headers = getallheaders() ?: [];
    $this->query = $_GET;
    $this->body = $_POST;
    $this->files = $_FILES;
    $this->cookies = $_COOKIE;
    $this->server = $_SERVER;

    $this->rawBody = file_get_contents('php://input');
    $this->json = json_decode($this->rawBody, true);
  }

  public function clientIp(): ?string
  {
    $ip = null;

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      $ip = trim($ipList[0]);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }

  public function method(): string
  {
    return $this->method;
  }

  public function uri(): string
  {
    return $this->uri;
  }

  public function headers(): array
  {
    return $this->headers;
  }

  public function query(?string $key = null): mixed
  {
    return $key ? $this->query[$key] ?? null : $this->query;
  }

  public function input(?string $key = null): mixed
  {
    return $key ? $this->body[$key] ?? null : $this->body;
  }

  public function file(?string $key = null): mixed
  {
    return $key ? $this->files[$key] ?? null : $this->files;
  }

  public function json(?string $key = null): mixed
  {
    return $key ? $this->json[$key] ?? null : $this->json;
  }
}