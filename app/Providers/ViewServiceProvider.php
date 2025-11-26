<?php

namespace App\Providers;

use App\Core\Container;
use League\Plates\Engine;

class ViewServiceProvider
{
  public static function register(Container $container): void
  {
    $container->singleton(Engine::class, fn() => new Engine(rootPath() . '/resources/views'));
  }
}