<?php

namespace App\Filament\Resources;

use App\Filament\Helper\PublishedColumn;
use App\Filament\Resources\ElectionResource\Pages;
use App\Models\Election;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ElectionResource extends Resource
{
    protected static ?string $model = Election::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Elections';

    protected static ?int $navigationSort = 28;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('published')
                    ->columnSpanFull(),
                $form->getRecord()?->date->isPast() ?
                Forms\Components\Placeholder::make('date')->content(fn (Election $record) => $record->date->toFormattedDateString()) :
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->after(Carbon::now()->addHours()),
                Forms\Components\TextInput::make('notification_topic')
                    ->label('Push Notification Topic')
                    ->required()
                    ->rules(['alpha_dash:ascii'])
                    ->live(true)
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->nullable()
                    ->placeholder('EU level election')
                    ->helperText('Assign a country to make it a national election'),
                Forms\Components\Select::make('languages')
                    ->label('Available languages')
                    ->relationship('languages', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('Keep empty to use all languages'),
                Forms\Components\Tabs::make('Tabs')->tabs([
                    Forms\Components\Tabs\Tab::make('Egg Screen')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('egg_title')
                                ->label('Title')
                                ->maxLength(255)
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\FileUpload::make('egg_image')
                                ->label('Image')
                                ->image()
                                ->directory('elections/egg_screen')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\RichEditor::make('egg_description')
                                ->label('Content')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('egg_yes_btn_text')
                                ->label('Text for Yes Button')
                                ->required()
                                ->columnSpan(1)
                                ->maxLength(255),
                            Forms\Components\TextInput::make('egg_yes_btn_link')
                                ->label('Link for Yes Button')
                                ->required()
                                ->columnSpan(1)
                                ->maxLength(255),
                            Forms\Components\TextInput::make('egg_no_btn_text')
                                ->label('Text for No Button')
                                ->required()
                                ->columnSpan(1)
                                ->maxLength(255),
                        ]),
                    Forms\Components\Tabs\Tab::make('Local Party Screen')
                        ->columns(2)
                        ->schema([
                            Forms\Components\FileUpload::make('lp_logo')
                                ->label('Logo')
                                ->image()
                                ->directory('elections/local_party_screen')
                                ->nullable()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('lp_text')
                                ->label('Text')
                                ->nullable()
                                ->columnSpan(1)
                                ->maxLength(255),
                            Forms\Components\TextInput::make('lp_link')
                                ->label('Link')
                                ->nullable()
                                ->columnSpan(1)
                                ->maxLength(255),
                        ]),
                ])->columnSpanFull(),

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
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable()
                    ->default('EU level election'),
                Tables\Columns\TextColumn::make('languages.name')
                    ->label('Available languages')
                    ->searchable()
                    ->sortable()
                    ->default('All'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('date', 'desc');
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
            'index' => Pages\ListElections::route('/'),
            'create' => Pages\CreateElection::route('/create'),
            'edit' => Pages\EditElection::route('/{record}/edit'),
        ];
    }
}
