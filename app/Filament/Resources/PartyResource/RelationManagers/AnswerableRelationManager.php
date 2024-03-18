<?php

namespace App\Filament\Resources\PartyResource\RelationManagers;

use App\Models\Answer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class AnswerableRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('statement_id')
                    ->relationship(
                        'statement',
                        'statement',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->whereNotIn('id', $this->ownerRecord->answers
                                // allow the currently selected statement. For new records $form-model is a string.
                                ->filter(fn (Answer $answer) => is_string($form->model) || $answer->statement_id !== $form->model->statement_id)
                                ->pluck('statement_id')
                            ),
                    )
                    ->required()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule) => $rule
                            ->where('answerable_id', $this->ownerRecord->id)
                            ->where('answerable_type', $this->ownerRecord->getMorphClass())
                    )
                    ->disabledOn('edit')
                    ->live(),
                Forms\Components\Select::make('answer')
                    ->options(Answer::$answerTexts)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('statement.statement')
            ->columns([
                Tables\Columns\TextColumn::make('statement.statement'),
                Tables\Columns\TextColumn::make('answer')->formatStateUsing(fn (Answer $answer) => $answer->getAnswerText()),
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
