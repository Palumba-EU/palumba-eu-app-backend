<?php

namespace App\Filament\Resources\PartyResource\RelationManagers;

use App\Filament\Helper\AnswerScale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StatementsRelationManager extends RelationManager
{
    protected static string $relationship = 'statements';

    protected static ?string $title = 'Answers';

    protected static ?string $label = 'Answer';

    protected static ?string $pluralLabel = 'Answers';

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
                Tables\Columns\SelectColumn::make('answer')
                    ->options(AnswerScale::$scale)
                    ->selectablePlaceholder(false),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Answer statement')
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action
                            // Filter for statements that belong to the same election as the party
                            ->recordSelectOptionsQuery(fn (Builder $query) => $query->election($this->getOwnerRecord()->election))
                            ->getRecordSelect(),
                        Forms\Components\Select::make('answer')
                            ->required()
                            ->options(AnswerScale::$scale),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Delete answer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
