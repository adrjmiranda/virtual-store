<?php

namespace App\Database\Type;

enum JoinType: string
{
  case INNER = 'INNER';
  case LEFT = 'LEFT';
  case RIGHT = 'RIGHT';
  case FULL = 'FULL';
  case CROSS = 'CROSS';
}