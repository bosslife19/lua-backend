<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
    public function register(Request $request){
        
        if($request->emailFirst){
            try {
                //code...
                $user =  User::create([
                    'email'=>$request->emailFirst
                ]);
                $token =  $user->createToken('main')->plainTextToken;
                $this->sendOtp(($user->email));
                return response()->json(['status'=>true, 'token'=>$token]);
            } catch (\Throwable $th) {
                //throw $th;
                \Log::info($th->getMessage());
                return response()->json(['status'=>false, 'error'=>$th->getMessage()]);
            }
           
        }
        $user = $request->user();
        if($request->password){
            $user->password = Hash::make($request->password);
            $user->save();
            
        }
        // dob,gender,fitnessLevel,fitnessGoals
        if($request->dob){
            $user->date_of_birth = $request->dob;
            $user->save();
        }
        if($request->gender){
            $user->gender = $request->gender;
            $user->save();
        }
        if($request->fitnessLevel){
            $user->fitness_level = $request->fitnessLevel;
            $user->save();
        }
        if($request->fitnessGoals){
            $user->fitness_goal = $request->fitessGoals;
            $user->save();
        }
        if($request->cycleBehaviour){
            $user->cycle_behaviour = $request->cycleBehaviour;
            $user->save();
        }
        if($request->legalName){
            $user->legal_name = $request->legalName;
            $user->name = $request->legalName;
            $user->save();
        }
        if($request->preferredName){
            $user->preferred_name = $request->preferredName;
            $user->save();
        }
        if($request->pronouns){
            $user->pronouns = $request->pronouns;
            $user->save();
        }
        if($request->supportStyle){
            $user->wellness_support_methods = $request->supportStyle;
            $user->save();
        }
        if($request->periodType){
            $user->period_type = $request->periodType;
            $user->save();
        }
        if($request->birthControl){
            $user->birth_control = $request->birthControl;
            $user->save();
        }
        if($request->cycleReg){
            $user->cycle_regularity = $request->cycleReg;
            $user->save();
        }
       if($request->flowLevel){
        $user->flow_type = $request->flowLevel;
        $user->save();
       }
       if($request->symptoms){
        $user->menstrual_symptoms = $request->symptoms;
        $user->save();
       }
       if($request->exerciseExperience){
        $user->exercise_experience = $request->exerciseExperience;
        $user->save();
       }
       if($request->exerciseFrequency){
        $user->exercise_frequency = $request->exerciseFrequency;
        $user->save();
       }
       if($request->activityLevel){
        $user->daily_activity_level = $request->activityLevel;
        $user->save();
       }
       if($request->additionalInfo){
        $user->additional_info = $request->additionalInfo;
        $user->save();
       }
       if($request->movementSpace){
        $user->movement_space = $request->movementSpace;
        $user->save();
       }
       if($request->medicalHistory){
        $user->health_conditions = $request->medicalHistory;
        $user->save();
       }
       if($request->movementConsiderations){
        $user->movement_considerations = $request->movementConsiderations;
        $user->save();
       }
       if($request->recentProcedure){
        $user->recent_surgical_procedures = $request->recentProcedure;
        $user->save();
       }
       if($request->pregnant){
        $user->pregnancy = $request->pregnant;
        $user->save();
       }
       if($request->movementResponse){
        $user->movement_response = $request->movementResponse;
        $user->save();
       }
       if($request->healthProv){
        $user->healthcare_provider = $request->healthProv;
        $user->save();
       }
       if($request->menstrualStart){

        $user->menstrual_start = $request->menstrualStart;
        $user->save();
       

       }
       if($request->additionalHealthInfo){
        $user->additional_health_info = $request->additionalHealthInfo;
        $user->save();
       }
        return response()->json(['status'=>true], 200);
       
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Provided email address or password is incorrect'
            ], 422);
        }

        $user = Auth::user();
       
        $token = $user->createToken('main')->plainTextToken;


        return response(
            [
                'status' => true,
                'token' => $token,
                'user' => $user,
                

            ]
        );
    }

    public function questionnaire(Request $request){
        $user = $request->user();
        
        $answers = $request['answers'];
        if(!$answers){
            return response()->json(['status'=>true], 200);
        }
        if($answers['Are you currently taking prescribed medications for a chronic medical condition?']){
            
            $question = $answers['Are you currently taking prescribed medications for a chronic medical condition?'];
            if($question =='Yes'){
                $user->medication_for_chronic_condition = true;
                $user->save();
            }else{
                $user->medication_for_chronic_condition = false;
                $user->save();
            }

        }
        if($answers['Do you currently have (or have had within the past 12 months) a bone, joint or soft tissue (muscle, ligament, or tendon) problem that could be made worse by becoming more physically active?']){
            $question = $answers['Do you currently have (or have had within the past 12 months) a bone, joint or soft tissue (muscle, ligament, or tendon) problem that could be made worse by becoming more physically active?'];
            if($question =='Yes'){
                $user->bone_or_ligament_problem = true;
                $user->save();
            }else{
                $user->bone_or_ligament_problem = false;
                $user->save();
            }
           
        }

        if($answers['Do you feel pain in your chest at rest, during daily activities, or when you do physical activity?']){
            $question = $answers['Do you feel pain in your chest at rest, during daily activities, or when you do physical activity?'];
            if($question=='Yes'){
                $user->chest_pain = true;
                $user->save();
            }else{
                $user->chest_pain =false;
                $user->save();

            }
            
        }
        if($answers['Do you lose balance because of dizziness or have you lost consciousness in the last 12 months?']){
            $question = $answers['Do you lose balance because of dizziness or have you lost consciousness in the last 12 months?'];
            if($question =='Yes'){
                $user->lost_consciousness = true;
                $user->save();
            }else{
                $user->lost_consciousness = false;
                $user->save();
            }
            
        }
        if($answers['Has your doctor ever said that you have a heart condition or high blood pressure?']){
            $question = $answers['Has your doctor ever said that you have a heart condition or high blood pressure?'];
            if($question =='Yes'){
                $user->heart_condition_or_hbp = true;
                $user->save();
            }else{
                $user->heart_condition_or_hbp = true;
                $user->save();
            }
        }
        if($answers['Has your doctor ever said that you should only do medically supervised physical activity?']){
            $question = $answers['Has your doctor ever said that you should only do medically supervised physical activity?'];
            if($question =='Yes'){
                $user->medically_supervised_activity = true;
                $user->save();
            }else{
                $user->medically_supervised_activity = false;
                $user->save();
            }
        }
        if($answers['Have you ever been diagnosed with another chronic medical condition (other than heart disease or high blood pressure)?']){
            $question = $answers['Have you ever been diagnosed with another chronic medical condition (other than heart disease or high blood pressure)?'];
            if($question =='Yes'){
                $user->other_chronic_condition = true;
                $user->save();
            }else{
                $user->other_chronic_condition = false;
                $user->save();
            }
        }
        return response()->json(['status'=>true], 200);
        
    }

    public function registerUser(Request $request){
        try {
            
            $user = $request->user();

            $request->validate(['email'=>'required', 'password'=>'required']);
            if($user->email == $request->email){
                $user->password = Hash::make($request->password);
              
                $user->save();
                return response()->json(['status'=>true, 'user'=>$user], 200);
            }
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            
            return response()->json(['status'=>true, 'user'=>$user], 200);
        } catch (\Exception $e) {
            //throw $th;
           
            return response()->json(['error'=>$e->getMessage()], 400);
        }
       
    }

    public function sendOtp($email)
    {
       
        $user = User::where('email', $email)->first();

        // Generate a 3-digit OTP
        $otp = random_int(100, 999);

        // Save OTP and its expiration time
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP via email
        try {
            //code...
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            //throw $th;
            \Log::info($e);
            \Log::info($e->getMessage());
        }
       
       
       

        return response()->json(['message' => 'OTP sent to your email.']);
    }

    public function verifyEmail(Request $request){
        try {
            $request->validate(['otp'=>'required']);

        $user = $request->user();
        if ($user->otp_code == intval($request->otp)) {
            // OTP is valid

            $user->update(['otp_code' => null, 'otp_expires_at' => null, 'email_verified_at' => now()]);


            return response()->json(['message' => 'OTP verified.', 'status'=>true]);
        } else {
            return response()->json(['error' => 'Invalid or expired OTP.']);
        }
        } catch (\Exception $e) {
            \Log::info($e);
            \Log::info($e->getMessage());
            return response()->json(['error'=>$e->getMessage()]);
        }
       
    }

    public function updateDetails(Request $request){

        
        $user = $request->user();
        if($request->name){
            $user->name = $request->name;
            $user->save();
        }
        
        if($request->email){
            $user->email = $request->email;
            $user->save();
        }
        if($request->password){
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json(['status'=>true, 'user'=>$user], 200);
    }
}
