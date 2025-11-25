<?php

use App\Middleware\Locale;
use App\Middleware\Maintenance;

return [
  'maintenance' => Maintenance::class,
  'locale' => Locale::class,
];