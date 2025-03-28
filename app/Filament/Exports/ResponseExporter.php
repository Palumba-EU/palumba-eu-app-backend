<?php

namespace App\Filament\Exports;

use App\Models\Response;
use App\Models\Statement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResponseExporter extends Exporter
{
    protected static ?string $model = Response::class;

    public static function modifyQuery(Builder $query): Builder
    {
        $electionId = session('global_election');

        if (is_null($electionId)) {
            return $query->with(['statements']);
        }

        return $query->with(['statements' => fn (BelongsToMany $query) => $query->election(session('global_election'))]);
    }

    public static function getColumns(): array
    {
        $electionId = session('global_election');

        $statementsQuery = Statement::query();
        if (! is_null($electionId)) {
            $statementsQuery = $statementsQuery->election($electionId);
        }
        $statements = $statementsQuery->orderBy('sort_index')->orderBy('statement')->get();

        return [
            ExportColumn::make('created_at'),
            ExportColumn::make('age'),
            ExportColumn::make('country.name'),
            ExportColumn::make('gender'),
            ExportColumn::make('level_of_education.name'),
            ExportColumn::make('going_to_vote'),
            ExportColumn::make('language_code'),
            ExportColumn::make('hashed_ip_address'),
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
