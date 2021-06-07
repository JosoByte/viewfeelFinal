<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Mail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\FirebaseException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected $auth;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
     protected $redirectTo = RouteServiceProvider::HOME;
    public function __construct(FirebaseAuth $auth) {
       $this->middleware('guest');
       $this->database = app('firebase.database');
       $this->auth = $auth;
    }
    protected function validator(array $data) {
       return Validator::make($data, [
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'email', 'max:255'],
          'password' => ['required', 'string', 'min:8', 'confirmed'],
       ]);
    }
    protected function register(Request $request) {
       $this->validator($request->all())->validate();
       $length = 20;
       $token = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length); //creates a random name for the file so its unique
       $this->database->getReference('users/'.$request->name)
         ->set([
               'badge' => ['newUser'],
               'bio' => $request->biografia,
               'img' => $request->hidden64ImageProfile,
               'level' => 1,
               'username' => $request->name,
               'usernameDisplay' => $request->name,
               'givenLikes' => [],
               'confirmed' => false,
               'tokenEmail' => $token,
            ]);
       $userProperties = [
          'email' => $request->input('email'),
          'emailVerified' => false,
          'password' => $request->input('password'),
          'displayName' => $request->input('name'),
          'disabled' => false,
       ];
       $this->sendConfirmationEmail($request->input('email'),$token,$request->name);
       $createdUser = $this->auth->createUser($userProperties);
       return redirect()->route('login');
    }
    public function sendConfirmationEmail($email,$token,$user){
      $to_name = $user;
      $to_email = $email;
      $data = array('name'=>$user, "body" => "Este es tu enlace para activar tu cuenta en ViewFeel: http://ec2-3-141-193-16.us-east-2.compute.amazonaws.com:8000/confirmMail/".$user."/".$token);
      Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
          $message->to($to_email, $to_name)->subject('Laravel ');
          $message->from('viewfeel0@gmail.com','Email de confirmación');
      });
  }
 }
