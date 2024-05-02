<?php

namespace App\Filament\Resources\PartyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class PartyPositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'positions';

    protected static ?string $title = 'Position per Topic';

    protected static ?string $label = 'Position';

    protected static ?string $pluralLabel = 'Positions';

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
                Tables\Columns\TextColumn::make('position'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Add position')
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('position')
                            ->numeric()
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Delete position'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->inverseRelationship('party_positions');
    }
}
