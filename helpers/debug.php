<?php

function dd(mixed ...$items): never
{
  foreach ($items as $item) {
    echo '<pre>';
    print_r($item);
    echo '<pre>';
    echo '<br/>';
  }
  die;
}