<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum GoingToVote: string implements HasLabel
{
    case No = 'no';
    case Maybe = 'maybe';
    case Yes = 'yes';

    public function getLabel(): ?string
    {
        return Str::headline($this->name);
    }
}
