<?php

namespace App\Filament\Exports;

use App\Models\Response;
use App\Models\Statement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ResponseExporter extends Exporter
{
    protected static ?string $model = Response::class;

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['statements']);
    }

    public static function getColumns(): array
    {
        $statements = Statement::query()->orderBy('sort_index')->orderBy('statement')->get();

        return [
            ExportColumn::make('created_at'),
            ExportColumn::make('age'),
            ExportColumn::make('country.name'),
            ExportColumn::make('gender'),
            ExportColumn::make('hashed_ip_address'),
            ExportColumn::make('language_code'),
            ExportColumn::make('uuid')
                ->label('UUID'),
            ...$statements->map(function (Statement $statement) {
                return ExportColumn::make($statement->statement)->state(function (Response $response) use ($statement) {
                    return $response->statements->first(fn ($s) => $s->id === $statement->id)?->pivot->answer;
                });
            })->toArray(),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your response export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
