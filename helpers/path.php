<?php

function rootPath(): string
{
  return dirname(dirname(__FILE__));
}

function filepath(string $path, string $ext = 'php'): string
{
  return rootPath() . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path) . ".{$ext}";
}

function getSqlFilePath(string $command, ?string $entry = null): string
{
  $fileName = $entry ? str_replace(':', '_', $command) . ".{$entry}" : 'fixtures.' . str_replace(':', '_', $command);
  return filepath("scripts.sql.{$fileName}", 'sql');
}
