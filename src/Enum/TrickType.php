<?php

namespace App\Enum;

enum TrickType: string
{
    case GRABS = 'grabs';
    case SPINS = 'spins';
    case FLIPS = 'flips';
    case SLIDES_GRINDS = 'slides grinds';
    case BUTTER_TRICKS = 'butter tricks';
    case ONE_FOOT_TRICKS = 'one foot tricks';
    case FREESTYLE_AERIAL_TRICKS = 'freestyle aerial tricks';
}
