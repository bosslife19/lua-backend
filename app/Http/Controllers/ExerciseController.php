<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function getAllWorkouts(Request $request){
        $user = $request->user();
        $exercises = Exercise::with('assignedUsers') // eager load assigned users
    ->where(function ($query) use ($user) {
        $query->whereDoesntHave('assignedUsers') // for exercises meant for all users
              ->orWhereHas('assignedUsers', function ($q) use ($user) {
                  $q->where('user_id', $user->id);
              });
    })
    ->latest()
    ->get();

        // $exercises = Exercise::latest()->get();
        foreach($exercises as $exercise){
            $exercise->image = asset('storage/' . $exercise->thumbnail);

        }
        
        return response()->json(['status'=>true, 'exercises'=>$exercises]);
    }
public function getLatest(){
    $exercise = Exercise::latest()->first();
    return response()->json(['status'=>true, 'exercise'=>$exercise]);
}
public function getWorkoutByExercise($exerciseId)
{
    $exercise = Exercise::with('workouts.exercises')->findOrFail($exerciseId);

    // Get the first associated workout
    $workout = $exercise->workouts->first();

    if (!$workout) {
        return response()->json([
            'status' => false,
            'message' => 'No workout associated with this exercise.'
        ], 404);
    }

    // Map image and video URLs for each exercise in the workout
    $workout->exercises->transform(function ($ex) {
        $ex->image = $ex->thumbnail ? asset('storage/' . $ex->thumbnail) : null;
        $ex->video_url = $ex->video ? asset('storage/' . $ex->video) : null;

        // $ex->video_urls = collect($ex->videos ?? [])->map(function ($videoPath) {
        //     return asset('storage/' . $videoPath);
        // });

        return $ex;
    });

    return response()->json([
        'status' => true,
        'workout' => $workout,
    ]);
}



    public function getSavedExercises(Request $request){
        $user = $request->user();
        $exercises = Exercise::where('saved', true)
        ->whereHas('assignedUsers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('assignedUsers')
        ->latest()
        ->get();

        foreach($exercises as $exercise){
            $exercise->image = asset('storage/' . $exercise->thumbnail);

        }

        return response()->json(['status'=>true, 'exercises'=>$exercises]);
    }

    public function saveExercise(Request $request)
{
    $user = $request->user();

    $exercise = Exercise::findOrFail($request->exercise_id);

    // Attach user to exercise (if not already attached)
    if (!$exercise->assignedUsers()->where('user_id', $user->id)->exists()) {
        $exercise->assignedUsers()->attach($user->id);
    }

    // Update saved to true
    $exercise->saved = true;
    $exercise->save();

    return response()->json([
        'status' => true,
        'message' => 'Exercise saved successfully.',
    ]);
}

}
