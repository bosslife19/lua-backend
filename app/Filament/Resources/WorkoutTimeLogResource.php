<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutTimeLogResource\Pages;
use App\Filament\Resources\WorkoutTimeLogResource\RelationManagers;
use App\Models\WorkoutTimeLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkoutTimeLogResource extends Resource
{
    protected static ?string $model = WorkoutTimeLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('seconds_watched'),
                TextColumn::make('youee'),
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
            'index' => Pages\ListWorkoutTimeLogs::route('/'),
            'create' => Pages\CreateWorkoutTimeLog::route('/create'),
            'edit' => Pages\EditWorkoutTimeLog::route('/{record}/edit'),
        ];
    }
}
