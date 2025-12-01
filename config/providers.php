<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoriesProvider;
use App\Providers\ViewServiceProvider;

return [
  AppServiceProvider::class,
  ViewServiceProvider::class,
  RepositoriesProvider::class
];