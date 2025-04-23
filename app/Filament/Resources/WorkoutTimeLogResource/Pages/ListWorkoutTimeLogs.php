<?php

namespace App\Filament\Resources\WorkoutTimeLogResource\Pages;

use App\Filament\Resources\WorkoutTimeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutTimeLogs extends ListRecords
{
    protected static string $resource = WorkoutTimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
