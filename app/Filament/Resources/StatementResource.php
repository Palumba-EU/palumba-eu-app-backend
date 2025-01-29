<?php

namespace App\Filament\Resources;

use App\Filament\Helper\ElectionSelect;
use App\Filament\Helper\PublishedColumn;
use App\Filament\Helper\SharedElectionFilter;
use App\Filament\Resources\StatementResource\Pages;
use App\Filament\Resources\StatementResource\RelationManagers\StatementWeightsRelationManager;
use App\Models\Statement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
                ElectionSelect::make()
                    ->disabledOn('edit'),
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
                Forms\Components\Checkbox::make('is_tutorial')
                    ->label('This is the tutorial statement')
                    ->live()
                    ->rules([
                        fn( Get $get ): \Closure => function (string $attribute, $value, \Closure $fail) use ($get, $form) {
                            if( is_null($get('election_id')) || !$value )
                                return;

                            $query = Statement::query()->election($get('election_id'))->where('is_tutorial', '=', true);
                            /** @var Statement|null $record */
                            $record = $form->getRecord();
                            if( !is_null($record) ){
                                $query = $query->where('id', '!=', $record->id);
                            }

                            if( $query->exists() ){
                                $fail("This election already has a tutorial question defined");
                            }
                        },
                    ])
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_index')
                    ->hidden(fn (Get $get): bool => $get('is_tutorial'))
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
