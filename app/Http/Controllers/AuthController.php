<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Authenticate;


class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validatedData = $request->validate([
            'email'=> 'email|required'
        ]);

        $email = $request->input('email');
        $user = Authenticate::where('email', $email)->firstOrFail();

        if($user){
            $userId  = $user->user_id;
            $httpRequest = "localhost:3000/authenticate/".$userId;
            $result = Http::timeout(180)->get($httpRequest)->json();

            if($result["result"]["message"]){
                $ses = array(
                    "token" => $request->input()['_token'],
                    "email" =>  $request->input()['email'],
                    "userID" => $userId
                );

                $request->session()->put('data', $ses);

                return view('welcome', $result);
            }
            else{
                return view('welcome',['message'=>"Authentication Failed Wait 5 minutes to try again"]);
            }
        }
        else{
            return view('welcome',['message'=>"No User By that Email"]);
        }
    }

    // public function profile(){
    //     $data = $request->session()->get('data');
    //     $email= email;
    //     $user = Authenticate::where('email', $email)->firstOrFail();
    //     return view('profile', $user);
    // }

    public function signup(Request $request)
    {
        $validatedData = $request->validate([
            'name'=> 'required',
            'email'=> 'email|required|unique:authenticates',
        ]);

        $email  = $request->input('email');
        $password = $request->input('password');

        $httpRequest = 'localhost:3000/users/newuser';
        $response = Http::post($httpRequest, [
            'email' => $email,
            'password' => $password,
        ])->json();

        if($response['data']){
            $user = new Authenticate;
            $user->name   = $request->input('name');
            $user->email  = $email;
            $user->user_id = $response['data']['uID'];
            $user->save();

            return view('welcome', [
                "appLink"=> $response['data']['app']
            ]);
        }

        else{
            return view("signup",['message'=>"Adding new User Failed"]);
        }
    }



    public function sendOTP()
    {
        $user = Authenticate::where('email', $email);

        if($user){
            return response(['message'=>"No User By that Email"]);
        }
        else{
            $userId  = $user->user_id;
            $httpRequest = 'localhost:3000/'+$userId+'/forgetMobile';

            $result = Http::post($httpRequest)->json();
            // dump($result);
            $token = $result->token;
            return view('auth/security/authOTP', $token);
        }

    }

    public function validateOTP()
    {
        $userId  = $user->user_id;
        $httpRequest = 'localhost:3000//'+$userId;
        $result = Http::get($httpRequest)->json();

        // dump($result);
        if($result->request){
            return view('welcome.index', $result);
        }
        else{
            return response(['message'=>"Authentication Failed"]);
        }

        return view('welcome.index', $result);
    }
}
