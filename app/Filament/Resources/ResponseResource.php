<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ResponseExporter;
use App\Filament\Resources\ResponseResource\Pages;
use App\Filament\Resources\ResponseResource\RelationManagers\StatementsRelationManager;
use App\Models\Response;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ResponseResource extends Resource
{
    protected static ?string $model = Response::class;

    protected static ?int $navigationSort = 70;

    protected static ?string $navigationGroup = 'Elections';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('age')
                    ->numeric(),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->required(),
                Forms\Components\TextInput::make('language_code')
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('gender')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('election.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country/Region')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('language_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hashed_ip_address')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('election')
                    ->relationship('election', 'name')
                    ->searchable()
                    ->preload(),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ResponseExporter::class)
                    ->columnMapping(false)
                    ->chunkSize(250)
                    ->modalDescription('Starts exporting the current responses in the background. You will be notified when the export is finished.')
                    ->modalSubmitActionLabel('Start export'),
            ])
            ->paginated([100, 250, 500])
            ->defaultPaginationPageOption(100);
    }

    public static function getRelations(): array
    {
        return [
            StatementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResponses::route('/'),
            'view' => Pages\ViewResponse::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
