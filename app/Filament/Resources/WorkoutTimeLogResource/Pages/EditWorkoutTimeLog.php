<?php

namespace App\Filament\Resources\WorkoutTimeLogResource\Pages;

use App\Filament\Resources\WorkoutTimeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutTimeLog extends EditRecord
{
    protected static string $resource = WorkoutTimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
