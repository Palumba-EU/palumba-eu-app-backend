<?php

namespace App\Filament\Resources;

use App\Filament\Helper\PublishedColumn;
use App\Filament\Resources\LocalPartyResource\Pages;
use App\Models\LocalParty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocalPartyResource extends Resource
{
    protected static ?string $model = LocalParty::class;

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationGroup = 'Elections';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $label = 'Local Candidate List';

    protected static ?string $pluralLabel = 'Local Candidate Lists';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('published')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->required(),
                Forms\Components\Select::make('party_id')
                    ->label('Associated EU Groups')
                    ->relationship('parties', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(512),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('local_parties')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('internal_notes')
                    ->default('')
                    ->hint('This information will not be shared publicly')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parties.name')
                    ->label('Associated EU Groups')
                    ->numeric(),
                Tables\Columns\TextColumn::make('link')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocalParties::route('/'),
            'create' => Pages\CreateLocalParty::route('/create'),
            'edit' => Pages\EditLocalParty::route('/{record}/edit'),
        ];
    }
}
