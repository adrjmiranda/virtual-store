<?php

function config(string $filename): array
{
  return require filepath("config.{$filename}");
}