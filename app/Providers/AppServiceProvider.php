<?php

namespace App\Providers;

use App\Core\Container;
use App\Http\Message\Error;
use App\Services\Application\SessionService;
use App\Utils\Cache;
use App\Utils\Logger;

class AppServiceProvider
{
  public static function register(Container $container): void
  {
    // Singleton
    $container->singleton(Logger::class, Logger::class);
    $container->singleton(Error::class, Error::class);
    $container->singleton(Cache::class, fn() => new Cache('/cache'));

    // Bind
    $container->bind(SessionService::class, SessionService::class);
  }
}