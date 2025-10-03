<?php

namespace App\Filament\Resources;

use App\Filament\Helper\ElectionSelect;
use App\Filament\Helper\PublishedColumn;
use App\Filament\Helper\SharedElectionFilter;
use App\Filament\Resources\PartyResource\Pages;
use App\Filament\Resources\PartyResource\RelationManagers\MoodImagesRelationManager;
use App\Filament\Resources\PartyResource\RelationManagers\PartyPositionsRelationManager;
use App\Filament\Resources\PartyResource\RelationManagers\PoliciesRelationManager;
use App\Filament\Resources\PartyResource\RelationManagers\StatementsRelationManager;
use App\Models\Election;
use App\Models\Party;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->disabledOn('edit')
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->required()
                    ->hex(),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->acceptedFileTypes(['image/svg+xml'])
                    ->hint('SVG files only')
                    ->directory('parties/logos')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(512),
                Forms\Components\TextInput::make('acronym')
                    ->required(),
                Forms\Components\Checkbox::make('in_parliament')
                    ->label('Currently in parliament'),
                Forms\Components\Select::make('unavailable_in_countries')
                    ->relationship(titleAttribute: 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                        $electionId = $get('election_id');
                        if (empty($electionId)) {
                            return $query;
                        }

                        $election = Election::query()->find($electionId);

                        if (is_null($election)) {
                            return $query;
                        }

                        return $query->parent($election->country_id);
                    })
                    ->label('Unavailable in')
                    ->multiple()
                    ->preload(),
                Forms\Components\Tabs::make('Tabs')->tabs([
                    Forms\Components\Tabs\Tab::make('Profile')
                        ->columns(2)
                        ->schema([
                            Forms\Components\RichEditor::make('profile_bio')
                                ->label('Bio')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\RichEditor::make('profile_affiliation')
                                ->label('Affiliation and Profile')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\RichEditor::make('profile_red_flags')
                                ->label('Red Flags')
                                ->required()
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('profile_link1')
                                ->label('Link 1')
                                ->required()
                                ->maxLength(512),
                            Forms\Components\TextInput::make('profile_link1_text')
                                ->label('Label for Link 1')
                                ->nullable(),

                            Forms\Components\TextInput::make('profile_link2')
                                ->label('Link 2')
                                ->required()
                                ->maxLength(512),
                            Forms\Components\TextInput::make('profile_link2_text')
                                ->label('Label for Link 2')
                                ->nullable(),
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
                Tables\Columns\TextColumn::make('election.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->copyable(),
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\IconColumn::make('in_parliament')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->trueColor('primary')
                    ->falseIcon('heroicon-o-x-mark')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
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
