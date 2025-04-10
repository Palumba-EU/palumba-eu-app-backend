<?php

namespace App\Filament\Resources;

use App\Filament\Helper\PublishedColumn;
use App\Filament\Resources\SponsorResource\Pages;
use App\Models\Sponsor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SponsorResource extends Resource
{
    protected static ?string $model = Sponsor::class;

    protected static ?int $navigationSort = 80;

    protected static ?string $navigationGroup = 'Global';

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('published')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('link')
                    ->url()
                    ->required()
                    ->maxLength(512),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('sponsors/logos')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('banner_image')
                    ->image()
                    ->directory('sponsors/banners')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('banner_link')
                    ->url()
                    ->required()
                    ->maxLength(512),
                Forms\Components\RichEditor::make('banner_description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('category')
                    ->required()
                    ->options([
                        'main sponsor' => 'Main sponsor',
                        'european partner' => 'European partner',
                        'national partner' => 'National partner',
                        'media' => 'Media',
                        'other' => 'Other',
                    ]),
                Forms\Components\Select::make('elections')
                    ->label('Sponsored elections')
                    ->multiple()
                    ->relationship('elections', 'name')
                    ->searchable()
                    ->preload()
                    ->hint('Keep empty to show sponsor in every election'),
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
                Tables\Columns\ImageColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('banner_image')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('banner_link')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => Str::ucfirst($state)),
                Tables\Columns\TextColumn::make('elections.name')
                    ->label('Sponsored elections')
                    ->searchable()
                    ->sortable()
                    ->default('All'),

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
            'index' => Pages\ListSponsors::route('/'),
            'create' => Pages\CreateSponsor::route('/create'),
            'edit' => Pages\EditSponsor::route('/{record}/edit'),
        ];
    }
}
