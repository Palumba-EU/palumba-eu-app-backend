<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartyResource\Pages;
use App\Filament\Resources\PartyResource\RelationManagers\MoodImagesRelationManager;
use App\Filament\Resources\PartyResource\RelationManagers\PoliciesRelationManager;
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

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $label = 'EU Group';

    protected static ?string $pluralLabel = 'EU Groups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->required()
                    ->hex()
                    ->columnSpanFull(),
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

                // 5D position
                Forms\Components\TextInput::make('p1')
                    ->label('Position 1')
                    ->required()
                    ->numeric()
                    ->columns(1),
                Forms\Components\TextInput::make('p2')
                    ->label('Position 2')
                    ->required()
                    ->numeric()
                    ->columns(1),
                Forms\Components\TextInput::make('p3')
                    ->label('Position 3')
                    ->required()
                    ->numeric()
                    ->columns(1),
                Forms\Components\TextInput::make('p4')
                    ->label('Position 4')
                    ->required()
                    ->numeric()
                    ->columns(1),
                Forms\Components\TextInput::make('p5')
                    ->label('Position 5')
                    ->required()
                    ->numeric()
                    ->columns(1),
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
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->copyable(),
                Tables\Columns\ImageColumn::make('logo'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PoliciesRelationManager::class,
            MoodImagesRelationManager::class,
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
