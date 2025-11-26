<?php

namespace App\Core;

class ServiceProvider
{
  public static function register(Container $container): void
  {
    $providers = config('providers');
    foreach ($providers as $provider) {
      $provider::register($container);
    }
  }
}