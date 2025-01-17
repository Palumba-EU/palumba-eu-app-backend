<?php

namespace App\Filament\Resources;

use App\Filament\Helper\ElectionSelect;
use App\Filament\Helper\SharedElectionFilter;
use App\Filament\Helper\PublishedColumn;
use App\Filament\Resources\PartyResource\Pages;
use App\Filament\Resources\PartyResource\RelationManagers\MoodImagesRelationManager;
use App\Filament\Resources\PartyResource\RelationManagers\PartyPositionsRelationManager;
use App\Filament\Resources\PartyResource\RelationManagers\PoliciesRelationManager;
use App\Filament\Resources\PartyResource\RelationManagers\StatementsRelationManager;
use App\Models\Party;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartyResource extends Resource
{
    protected static ?string $model = Party::class;

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationGroup = 'Elections';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $label = 'Electable party';

    protected static ?string $pluralLabel = 'Electable parties';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('published')
                    ->columnSpanFull(),
                ElectionSelect::make()
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->required()
                    ->hex(),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('parties/logos')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(512),
                Forms\Components\TextInput::make('acronym')
                    ->required(),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->copyable(),
                Tables\Columns\ImageColumn::make('logo'),
            ])
            ->filters([
                SharedElectionFilter::make(),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PoliciesRelationManager::class,
            MoodImagesRelationManager::class,
            StatementsRelationManager::class,
            PartyPositionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParties::route('/'),
            'create' => Pages\CreateParty::route('/create'),
            'edit' => Pages\EditParty::route('/{record}/edit'),
        ];
    }
}
