<?php

namespace App\Utils;

class Logger
{
  public const string INFO = 'INFO';
  public const string WARNING = 'WARNING';
  public const string DEBUG = 'DEBUG';
  public const string ERROR = 'ERROR';

  private string $logFile;

  public function __construct(string $filename = 'app.log')
  {
    $this->filename($filename);
  }

  public function filename(string $name): self
  {
    $this->logFile = "/var/store/logs/{$name}";

    $dir = dirname($this->logFile);


    if (!is_dir($dir)) {
      mkdir(dirname($dir), 0755, true);
    }

    if (!file_exists($this->logFile)) {
      touch($this->logFile);
      chmod($this->logFile, 0664);
    }

    return $this;
  }

  public function write(string $message, string $level = self::ERROR): void
  {
    $date = date('Y-m-d H:i:s');
    file_put_contents($this->logFile, "[{$date}] [{$level}] {$message}\n", FILE_APPEND);
  }

  public function info(string $message): void
  {
    $this->write($message, self::INFO);
  }

  public function warning(string $message): void
  {
    $this->write($message, self::WARNING);
  }

  public function debug(string $message): void
  {
    $this->write($message, self::DEBUG);
  }

  public function error(string $message): void
  {
    $this->write($message, self::ERROR);
  }
}