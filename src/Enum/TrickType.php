<?php

namespace App\Enum;

enum TrickType: string
{
    case GRABS = 'grabs';
    case SPINS = 'spins';
    case FLIPS = 'flips';
    case SLIDES_GRINDS = 'slides_grinds';
    case BUTTER_TRICKS = 'butter_tricks';
    case ONE_FOOT_TRICKS = 'one_foot_tricks';
    case FREESTYLE_AERIAL_TRICKS = 'freestyle_aerial_tricks';
}
