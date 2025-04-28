<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseResource\Pages;
use App\Filament\Resources\ExerciseResource\RelationManagers;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                TextInput::make('description'),
                DatePicker::make('scheduled_for'),
                Select::make('assignedUsers')->multiple()->relationship('assignedUsers', 'name')
                ->label('Assign to Specific Users')
                ->searchable()
                ->placeholder('Leave empty to assign to everyone')
                ->preload(),
                FileUpload::make('videos')
                ->label('Workout Videos')
                ->directory('workouts')
                ->acceptedFileTypes(['video/mp4', 'video/mov', 'video/webm'])
                ->maxSize(51200) // ~50MB
                ->multiple() // <-- IMPORTANT
                ->required()
                ->visibility('public'), // important for frontend access, // important for frontend access
            
            TextInput::make('duration')->placeholder('eg. 30 Minutes')->integer(),
            TextInput::make('equipments')->placeholder('e.g. Dumbbell, yoga mat'),
            MarkdownEditor::make('instructions'),
            MarkdownEditor::make('trainer_notes')->label("Your Trainer's notes"),
            TextInput::make('working_muscles')->nullable(),
            TextInput::make('supporting_muscles')->nullable(),
            
            Select::make('level')->options(['Beginner', 'Intermediate', 'Advanced', 'Professional']),
            FileUpload::make('thumbnail')->label('Workout Image/Thumbnail')
            ->directory('thumbnails')
            ->acceptedFileTypes(['image/jpg', 'image/png', 'image/jpeg'])
            ->maxSize(51200) // ~50MB
            ->required()
            ->visibility('public'), // important for frontend access

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('description'),
                
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
            'index' => Pages\ListExercises::route('/'),
            'create' => Pages\CreateExercise::route('/create'),
            'edit' => Pages\EditExercise::route('/{record}/edit'),
        ];
    }
}
