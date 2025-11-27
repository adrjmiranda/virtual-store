<?php

require_once __DIR__ . '/../vendor/autoload.php';

const ALL_TABLES = [
  'users',
  'categories',
  'products',
  'addresses',
  'payments',
  'orders',
  'order_payments',
  'carts',
  'audit_logs',
  'event_logs',
  'product_options',
  'product_variants',
  'product_option_values',
  'product_variant_values',
  'order_discounts',
  'images',
  'order_items',
  'product_category',
  'cart_items',
  'coupons',
  'inventory_changes',
];

$dotenv = Dotenv\Dotenv::createImmutable(rootPath());
$dotenv->load();

if (!isset($argv[1]))
  throw new Exception("Failed to execute command. [command] is missing.", 500);

$command = $argv[1];
$entry = $argv[2] ?? null;


$dbuser = env('DB_USER');
$dbname = $command === 'create:db' ? 'postgres' : env('DB_NAME');

if ($command === 'create:tables') {
  if ($entry !== null) {
    throw new Exception("The `create:tables` command does not expect parameters.", 500);
  }

  foreach (ALL_TABLES as $table) {
    echo "Creating table: {$table}\n\n";
    $sqlFile = getSqlFilePath('create:table', $table);
    $cmd = "docker exec -i postgres-db psql -U {$dbuser} -d {$dbname}";
    passthru("cat {$sqlFile} | {$cmd}");
  }
} else {
  $sqlFile = getSqlFilePath($command, $entry);

  if (!file_exists($sqlFile)) {
    throw new Exception("Sql file {$sqlFile} does not exist.", 1);
  }

  echo "Executing command: {$command}\n\n";

  $cmd = "docker exec -i postgres-db psql -U {$dbuser} -d {$dbname}";
  passthru("cat {$sqlFile} | {$cmd}");
}
