<?php

function filepath(string $path): string
{
  return rootPath() . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path) . '.php';
}

function demand(string $path): void
{
  require filepath($path);
}

function demandOnce(string $path): void
{
  require_once filepath($path);
}

function enclose(string $path): void
{
  include filepath($path);
}

function encloseOnce(string $path): void
{
  include_once filepath($path);
}