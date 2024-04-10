<?php

namespace App\Filament\Resources\ResponseResource\RelationManagers;

use App\Filament\Helper\AnswerScale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StatementsRelationManager extends RelationManager
{
    protected static string $relationship = 'statements';

    protected static ?string $title = 'Answers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('statement')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('statement')
            ->columns([
                Tables\Columns\TextColumn::make('statement'),
                Tables\Columns\TextColumn::make('answer')
                    ->formatStateUsing(fn (?int $state) => AnswerScale::getLabel($state))
                    ->placeholder('Skipped'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
