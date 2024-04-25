<?php

namespace App\Filament\Widgets;

use App\Models\Response;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $responsesTotal = Response::query()->count();
        $responses7d = Response::query()->since(now()->subWeek())->count();
        $responses24h = Response::query()->since(now()->subDay())->count();

        return [
            Stat::make('Responses total', $responsesTotal),
            Stat::make('Responses in the last 7 days', $responses7d),
            Stat::make('Responses in the last 24h', $responses24h),
        ];
    }
}
