<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\WorkoutTimeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutTimeLogController extends Controller
{
    public function logTime(Request $request)
    {
        $request->validate([
            'seconds_watched' => 'required|integer|min:1',
            'exercise_id' => 'nullable|integer|exists:exercises,id',
        ]);
    
        try {
            $user = $request->user();
            $date = now()->toDateString();
    
            $log = WorkoutTimeLog::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();
    
            if ($log) {
                // Update seconds_watched using raw SQL increment
                $log->increment('seconds_watched', $request->seconds_watched);
                $log->status = $request->status;
                $log->save();
            } else {
                // Create new log
                $log = WorkoutTimeLog::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'seconds_watched' => $request->seconds_watched,
                    'exercises_done' => [],
                    'status'=>$request->status
                ]);
            }
    
            // Handle exercise logging if provided
            if ($request->filled('exercise_id')) {
                $exercises = $log->exercises_done ?? [];
    
                // \Log::info($request->exercise_id);
                if (!in_array($request->exercise_id, $exercises)) {
                    $exercises[] = $request->exercise_id;
                    $log->exercises_done = $exercises;
                    $log->save();
                }
            }
    
            return response()->json([
                'status' => true,
                'log' => $log
            ]);
        } catch (\Exception $e) {
            // \Log::error('Watch time log failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }
    public function getWeeklyWorkoutLogs(Request $request)
    {
        $user = $request->user();
    
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday
    
        // Get all logs for the current week
        $logs = WorkoutTimeLog::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();
    
        // Sum total seconds watched for the week
        $totalSeconds = $logs->sum('seconds_watched');
        $totalMinutes = round($totalSeconds / 60); // round to nearest minute
    
        // Map logs to days
        $formattedLogs = $logs->map(function ($log) {
            return [
                'day' => Carbon::parse($log->date)->format('l'),
                'time' => Carbon::parse($log->created_at)->format('h:i A'),
                'status' => $log->status,
            ];
        });
    
        // Fill missing days with "Scheduled"
        $daysOfWeek = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
        $logsByDay = $daysOfWeek->map(function ($day) use ($formattedLogs) {
            $found = $formattedLogs->firstWhere('day', $day);
            return $found ?: [
                'day' => $day,
                'time' => '',
                'status' => 'Missed',
            ];
        });
    
        return response()->json([
            'logs' => $logsByDay,
            'total_minutes' => $totalMinutes,
        ]);
    }  
    
    public function getMonthlyExerciseCount(Request $request)
{
    try {
        $user = $request->user();

        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        // Fetch all logs in current month for the user
        $logs = WorkoutTimeLog::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->pluck('exercises_done');

        // Flatten and collect unique exercise IDs
        $allExercises = collect($logs)
            ->flatten()
            ->unique()
            ->filter()
            ->values();

        return response()->json([
            'status' => true,
            'count' => $allExercises->count(),
            'exercises' => $allExercises,
        ]);
    } catch (\Exception $e) {
        \Log::error('Failed to fetch monthly exercise count: ' . $e->getMessage());
        return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
    }
}


public function getWorkoutChart(Request $request){
    $user = $request->user();

    // Start from Sunday of the current week
    $startOfWeek = Carbon::now()->startOfWeek(); // change to ->startOfWeek(Carbon::MONDAY) if you want weeks to start on Monday
    $endOfWeek = $startOfWeek->copy()->endOfWeek();

    // Get logs for the week
    $logs = WorkoutTimeLog::where('user_id', $user->id)
        ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
        ->get()
        ->map(function ($log) {
            return [
                'date' => $log->date,
                'seconds_watched' => $log->seconds_watched,
            ];
        });

    return response()->json(['status' => true, 'logs' => $logs]);
}

public function checkDailyLog(Request $request)
{
    $user = $request->user();
  
    $yesterday = now()->subDay()->toDateString();

    $hasLoggedToday = WorkoutTimeLog::where('user_id', $user->id)
        ->whereDate('created_at', $yesterday)
        ->exists();

    if (!$hasLoggedToday) {
        // Create the notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'missed_workout',
            'title' => "Missed workout alert",
            'message' => "You missed yesterday's session. Let's get back on track today",
            'read' => false,
        ]);
    }

    return response()->json([
        'status' => true,
        'missed' => !$hasLoggedToday,
    ]);
}

}
