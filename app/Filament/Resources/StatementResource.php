<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatementResource\Pages;
use App\Models\Statement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatementResource extends Resource
{
    protected static ?string $model = Statement::class;

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->unique(Statement::class, ignoreRecord: true)
                    ->numeric()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('w1')
                    ->label('Weight 1')
                    ->required()
                    ->numeric()
                    ->minValue(-100)
                    ->maxValue(+100)
                    ->columns(1),
                Forms\Components\TextInput::make('w2')
                    ->label('Weight 2')
                    ->required()
                    ->numeric()
                    ->minValue(-100)
                    ->maxValue(+100)
                    ->columns(1),
                Forms\Components\TextInput::make('w3')
                    ->label('Weight 3')
                    ->required()
                    ->numeric()
                    ->minValue(-100)
                    ->maxValue(+100)
                    ->columns(1),
                Forms\Components\TextInput::make('w4')
                    ->label('Weight 4')
                    ->required()
                    ->numeric()
                    ->minValue(-100)
                    ->maxValue(+100)
                    ->columns(1),
                Forms\Components\TextInput::make('w5')
                    ->label('Weight 5')
                    ->required()
                    ->numeric()
                    ->minValue(-100)
                    ->maxValue(+100)
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
                Tables\Columns\TextColumn::make('statement')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_index')
                    ->numeric()
                    ->sortable(),
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
            //
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
