<?php

namespace App\Filament\Resources\StatementResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class StatementWeightsRelationManager extends RelationManager
{
    protected static string $relationship = 'weights';

    protected static ?string $title = 'Weight per Topic';

    protected static ?string $label = 'Weight';

    protected static ?string $pluralLabel = 'Weights';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('weight'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Add weight')
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('weight')
                            ->numeric()
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Delete weight'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->inverseRelationship('statement_weights');
    }
}
