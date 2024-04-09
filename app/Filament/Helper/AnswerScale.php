<?php

namespace App\Filament\Helper;

class AnswerScale
{
    public static $scale = [
        -2 => 'Fully disagree',
        -1 => 'Somewhat disagree',
        0 => 'Neutral',
        1 => 'Somewhat agree',
        2 => 'Fully agree',
    ];

    public static function getLabel(int $number)
    {
        return self::$scale[$number];
    }
}
