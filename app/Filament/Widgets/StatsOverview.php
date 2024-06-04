<?php

namespace App\Filament\Widgets;

use App\Models\Response;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $responsesTotal = Response::query()->count();
        $responses7d = Response::query()->since(now()->subWeek())->count();
        $responses7dChartData = Trend::model(Response::class)
            ->between(
                start: now()->subWeek(),
                end: now(),
            )
            ->perDay()
            ->count();

        $responses24h = Response::query()->since(now()->subDay())->count();
        $responses24hChartData = Trend::model(Response::class)
            ->between(
                start: now()->subDay(),
                end: now(),
            )
            ->perHour()
            ->count();

        return [
            Stat::make('Responses total', $responsesTotal),
            Stat::make('Responses in the last 7 days', $responses7d)
                ->chart($responses7dChartData->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),
            Stat::make('Responses in the last 24h', $responses24h)
                ->chart($responses24hChartData->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),
        ];
    }
}
