<?php

namespace App\Filament\Widgets;

use App\Models\Exercise;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users',User::count())
            ->description('Total users on the platform')
            ->descriptionIcon('heroicon-m-users',IconPosition::Before)
            ->chart([1,3,5,10,20,40])
            ->color('success'),

            Stat::make('Workouts', Exercise::count())
            ->description('Total workouts uploaded')
           
            ->chart([1,3,5,10,20,40])
            ->color('success'),
        ];
    }
}
