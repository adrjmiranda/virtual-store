<?php

namespace App\Utils;

class Cache
{
  private string $cacheDir;
  private int $defaultTtl;

  public function __construct(string $cacheDir, int $defaultTtl = 3600)
  {
    $this->cacheDir = sys_get_temp_dir() . rtrim($cacheDir, '/');
    $this->defaultTtl = $defaultTtl;

    if (!is_dir($this->cacheDir)) {
      mkdir($this->cacheDir, 0755, true);
    }
  }

  private function getCacheFile(string $key): string
  {
    return $this->cacheDir . '/' . md5($key) . '.cache';
  }

  public function has(string $key): bool
  {
    $file = $this->getCacheFile($key);

    if (!file_exists($file)) {
      return false;
    }

    $cacheData = unserialize(file_get_contents($file));

    if (!isset($cacheData['expire'], $cacheData['data'])) {
      return false;
    }

    return time() < $cacheData['expire'];
  }

  public function get(string $key): mixed
  {
    $file = $this->getCacheFile($key);
    if (!$this->has($key)) {
      return null;
    }
    $cacheData = unserialize(file_get_contents($file));
    return $cacheData['data'];
  }

  public function set(string $key, mixed $data, ?int $ttl): void
  {
    $file = $this->getCacheFile($key);
    $ttl ??= $this->defaultTtl;

    $cacheData = [
      'expire' => time() + $ttl,
      'data' => $data
    ];

    file_put_contents($file, serialize($cacheData));
  }

  public function delete(string $key): void
  {
    $file = $this->getCacheFile($key);
    if (file_exists($file)) {
      unlink($file);
    }
  }

  public function clear(): void
  {
    $files = glob("{$this->cacheDir}/*.cache");
    foreach ($files as $file) {
      if (file_exists($file)) {
        unlink($file);
      }
    }
  }
}