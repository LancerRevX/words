<?php

namespace App\Enums;

enum SortType : string
{
    case AToZ = 'atoz';
    case ZToA = 'ztoa';
    case New = 'new';
    case Old = 'old';
}
