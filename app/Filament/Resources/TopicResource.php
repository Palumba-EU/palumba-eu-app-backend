<?php

namespace App\Filament\Resources;

use App\Filament\Helper\ElectionSelect;
use App\Filament\Helper\PublishedColumn;
use App\Filament\Helper\SharedElectionFilter;
use App\Filament\Resources\TopicResource\Pages;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?int $navigationSort = 60;

    protected static ?string $navigationGroup = 'Elections';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

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
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                        $livewire->validateOnly($component->getStatePath());
                    }),
                Forms\Components\ColorPicker::make('color')
                    ->hex()
                    ->required(),
                Forms\Components\TextInput::make('extreme1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('extreme1_emojis')
                    ->required(),
                Forms\Components\RichEditor::make('extreme1_details')
                    ->required()
                    ->default('')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('extreme2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('extreme2_emojis')
                    ->required(),
                Forms\Components\RichEditor::make('extreme2_details')
                    ->required()
                    ->default('')
                    ->columnSpanFull(),
                Forms\Components\Select::make('statements')
                    ->label('Associated statements')
                    ->relationship(name: 'statements', titleAttribute: 'statement', modifyQueryUsing: function (Builder $query, Get $get) {
                        $electionId = $get('election_id');
                        if (! is_null($electionId)) {
                            $query->election($electionId);
                        }
                    })
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('icon')
                    ->image()
                    ->required()
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('icon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('extreme1')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('extreme2')
                    ->searchable()
                    ->sortable()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
