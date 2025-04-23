<?php

namespace App\Filament\Resources\ExerciseResource\Pages;

use App\Filament\Resources\ExerciseResource;
use App\Models\Notification;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExercise extends CreateRecord
{
    protected static string $resource = ExerciseResource::class;

    protected function afterCreate(): void
    {
        // Notify all users (or filter as needed)
        $users = User::all();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_videos',
                'title' => 'New Workouts  Available',
                'message' => 'A new workout video has been uploaded by your trainer.',
                'read' => false,
                
            ]);
        }
    }
}
