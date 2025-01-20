<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum LevelOfEducation: int implements HasLabel
{
    case NoFormal = 0;
    case Primary = 1;
    case LowerSecondary = 2;
    case UpperSecondary = 3;
    case PostSecondaryNonTertiary = 4;
    case ShortCycleTertiary = 5;
    case Bachelors = 6;
    case Masters = 7;
    case Doctorate = 8;

    public function getLabel(): ?string
    {
        return Str::headline($this->name);
    }
}
