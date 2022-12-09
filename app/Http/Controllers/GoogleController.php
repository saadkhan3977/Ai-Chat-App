<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    public function sendmsg(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.openai.com/v1/completions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
                "model": "text-davinci-003",
                "prompt": "'.$request->msg.'",
                "temperature": 0.9,
                "max_tokens": 150,
                "top_p": 1,
                "frequency_penalty": 0,
                "presence_penalty": 0.6,
                "stop": [" Human:", " AI:"]
                }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'model: text-davinci-003',
            'prompt: as',
            'Authorization: Bearer key url https://beta.openai.com/account/api-keyss'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($response);
        return response()->json(['success'=>$res->choices]);
    }
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
      
            $user = Socialite::driver('google')->user();
       
            $finduser = User::where('google_id', $user->id)->first();
       
            if($finduser){
       
                Auth::login($finduser);
      
                return redirect()->intended('dashboard');
       
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('12345678')
                ]);
      
                Auth::login($newUser);
      
                return redirect()->intended('dashboard');
            }
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
