<?php

namespace App\Filament\Resources\PartyResource\RelationManagers;

use App\Models\Scopes\PublishedScope;
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
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([PublishedScope::class]))
            ->recordTitleAttribute('statement')
            ->columns([
                Tables\Columns\TextColumn::make('statement'),
                Tables\Columns\TextColumn::make('answer'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->withoutGlobalScopes([PublishedScope::class]))
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('answer')
                            ->required()
                            ->options([
                                -2 => 'Strongly disagree',
                                -1 => 'Disagree',
                                0 => 'Neutral',
                                1 => 'Agree',
                                2 => 'Strongly agree',
                            ]),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
