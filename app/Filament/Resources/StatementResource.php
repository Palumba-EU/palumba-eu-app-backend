<?php

namespace App\Filament\Resources;

use App\Filament\Helper\SharedElectionFilter;
use App\Filament\Helper\PublishedColumn;
use App\Filament\Resources\StatementResource\Pages;
use App\Filament\Resources\StatementResource\RelationManagers\StatementWeightsRelationManager;
use App\Models\Statement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatementResource extends Resource
{
    protected static ?string $model = Statement::class;

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationGroup = 'Elections';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('published')
                    ->columnSpanFull(),
                Forms\Components\Select::make('election_id')
                    ->relationship('election', 'name')
                    ->required()
                    ->disabledOn('edit')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('statement')
                    ->required()
                    ->maxLength(1024)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('emojis')
                    ->required(),
                Forms\Components\RichEditor::make('details')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('footnote')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_index')
                    ->required()
                    ->numeric()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                PublishedColumn::make('published')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('election.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('statement')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_index')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('sort_index')
            ->filters([
                SharedElectionFilter::make(),
                Tables\Filters\TrashedFilter::make(),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StatementWeightsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatements::route('/'),
            'create' => Pages\CreateStatement::route('/create'),
            'edit' => Pages\EditStatement::route('/{record}/edit'),
        ];
    }
}
