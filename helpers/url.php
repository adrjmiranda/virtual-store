<?php

function baseUrl(): string
{
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'];

  $base = "{$scheme}://{$host}";

  return $base;
}