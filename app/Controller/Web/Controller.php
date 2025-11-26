<?php

namespace App\Controller\Web;

use App\Utils\Cache;
use League\Plates\Engine;

abstract class Controller
{
  private Engine $engine;
  private Cache $cache;

  protected int $defaultViewTtl = 300;

  public function __construct(Engine $engine, Cache $cache)
  {
    $this->engine = $engine;
    $this->cache = $cache;
  }

  protected function view(string $template, array $data = [], bool $cache = false, ?int $ttl = null): string
  {
    $key = $template . ':' . md5(serialize($data));
    if ($cache && $this->cache->has($key)) {
      return $this->cache->get($key);
    }

    $html = $this->engine->render($template, $data);

    if ($cache) {
      $this->cache->set($key, $html, $ttl ?? $this->defaultViewTtl);
    }

    return $html;
  }
}