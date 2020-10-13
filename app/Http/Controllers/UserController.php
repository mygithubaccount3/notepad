<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request) {
        $username = $request -> session()->get('username');
        if(isset($username) && strlen($username) > 0) {
            return redirect('/welcome');
        } else {
            return redirect('/login');
        }
    }

    public function logIn(Request $request)
    {
        if($request->isMethod('POST')) {
            $credentials = $request->only('username', 'password');
            if(isset($credentials['username']) && isset($credentials['password'])) {
                if(strlen($credentials['username']) < 1 || strlen($credentials['password']) < 1) {
                    Log::error('Username and password are required');
                    $request->session()->flash('error', 'Username and password are required');
                    return redirect('/login');
                } else {
                    if(preg_match('/^(?=.{1,20}$)[a-zA-Z0-9]+$/', $credentials['username'])) {
                        if (Auth::attempt($credentials)) {
                            Log::info('Login success '. $credentials['username']);
                            $request->session()->put('username', $credentials['username']);
                            return redirect()->intended();
                        } else {
                            Log::error('Login fail' . $credentials['username'] . ' ' . $credentials['password']);
                            $request->session()->flash('error', 'Incorrect password');
                            return redirect('/login');
                        }
                    } else {
                        Log::error('Username can have only letters and digits');
                        $request->session()->flash('error', 'Username can have only letters and digits');
                        return redirect('/login');
                    }
                }
            }
        } else if ($request->isMethod('GET')) {
            return view('login');
        }
        return redirect('/login');
    }
}
