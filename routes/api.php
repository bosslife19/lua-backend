<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkoutTimeLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register-email', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/update-message', [NotificationController::class, 'newMessageFromAdmin']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/register-user',[AuthController::class,'registerUser']);
    Route::post('/register/questionnaire',[AuthController::class,'questionnaire']);
     Route::get('/all-exercises', [ExerciseController::class, 'getAllWorkouts']);
     Route::get('/saved-exercises', [ExerciseController::class, 'getSavedExercises']);
     Route::get('/exercises/{id}', [ExerciseController::class, 'getWorkoutByExercise']);
     Route::post('/update-watch-time', [WorkoutTimeLogController::class, 'logTime']);
     Route::get('/get-weekly', [WorkoutTimeLogController::class, 'getWeeklyWorkoutLogs']);
     Route::get("/get-latest-exercise", [ExerciseController::class, 'getLatest']);
     Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
     Route::get('/workout-logs/week', [WorkoutTimeLogController::class, 'getWorkoutChart']);
     Route::post('/update-details', [AuthController::class, 'updateDetails']);
     Route::post("/create-notification", [NotificationController::class, 'createNotification']);
     Route::get('/get-unread-notifications', [NotificationController::class, 'getUnreadNotifications']);
     Route::get('/check-daily-log', [WorkoutTimeLogController::class,'checkDailyLog']);
     Route::post('/save-exercise', [ExerciseController::class, 'saveExercise']);

     Route::post("/workout-range-summary", [WorkoutTimeLogController::class, 'workoutRangeSummary']);
     
     Route::post('/mark-notifications-as-read', [NotificationController::class, 'markNotificationsAsRead']);
     Route::get('/get-monthly-exercise-count', [WorkoutTimeLogController::class, 'getMonthlyExerciseCount']);
    
});
