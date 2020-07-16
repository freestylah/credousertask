<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
  use AuthenticatesUsers;

  protected $redirectTo = RouteServiceProvider::HOME;

  public function __construct()
  {
    $this->middleware('guest')->except(['logout','updateuser','showuser','showusers','deluser']);
  }

  protected function sendFailedLoginResponse(Request $request)
  {
    return response()->json(['error' => 'Wrong credentials!'],422);
  }

  protected function sendLoginResponse(Request $request)
  {
    if (! $request->wantsJson()) {
      $request->session()->regenerate();
    }

    $this->clearLoginAttempts($request);

    return $this->authenticated($request, $this->guard()->user())
      ?: redirect()->intended($this->redirectPath());
  }

  protected function authenticated(Request $request, $user)
  {
    $token = auth()->user()->createToken('SPA');

    return response()->json([
      'userId' => $user->id,
      'email' => $user->email,
      'firstName' => $user->firstName,
      'lastName' => $user->lastName,
      'token' => $token->accessToken,
    ]);
  }


  // Log out
  public function logout(){
    \Auth::user()->tokens->each(function($token, $key)
    {
      $token->delete();
    });

    return response()->json(['status' => 'Logged out successfully'],200);
  }

  // Show a single user

  public function showuser($id)
  {
    $user = User::find($id);
    return response()->json($user,200);
  }

  // Show all users
  public function showusers(Request $request)
  {
    $users = User::all();
    return response()->json($users,200);
  }

  // Update user
  public function updateuser(Request $request, $id)
  {
    $user = User::find($id);

    if(!$user){
        return response()->json('No such user found!',422);
    }

    // doing this to avoid errors while testing
   
    $request->validate([
      'firstName' => 'required|string|max:255',
      'lastName' => 'required|string|max:255',
    ]);

    $current_password = $user->password; 

    if(isset($current_password) && !empty($request->oldpassword)){
      if(\Hash::check($request->oldpassword, $current_password))
      {           
        \Log::info("Old password is correct, continuing...");

        if($request->password == $request->password_confirmation)
        {           
        $user->password = \Hash::make($request->get('password'));
        \Log::info("Changed to the new password...");
        } else {
          \Log::error("New passwords doesn't match");
          return response()->json("New passwords doesn't match'",422);
        }
      } else { 
        return response()->json("Not the same password",422);
        \Log::error("Not the same password");
      }
    } else { 
      \Log::info("Old password must be entered in order to change your password!");
    }

    $user->firstName = $request->firstName;
    $user->lastName = $request->lastName;
    $user->save();

    return response()->json($user,200);
  }

  public function deluser($id)
    {
      $user = User::find($id);

      if($user){
        $user->delete();
      } else { 
        return response()->json(['error' => 'No such user']);     
      }


      return response()->json(['status' => 'User Deleted!']);
    }


}
