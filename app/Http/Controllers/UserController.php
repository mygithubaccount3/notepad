<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
                    $request->session()->flash('message', 'Username and password are required');
                    return redirect('/login');
                } else {
                    if(preg_match('/^(?=.{1,20}$)[a-zA-Z0-9]+$/', $credentials['username'])) {
                        if (Auth::attempt($credentials)) {
                            Log::info('Login success '. $credentials['username']);
                            $request->session()->put('username', $credentials['username']);
                            return redirect()->intended();
                        } else {
                            Log::error('Login fail' . $credentials['username'] . ' ' . $credentials['password']);
                            $request->session()->flash('message', 'Incorrect password');
                            return redirect('/login');
                        }
                    } else {
                        Log::error('Username can have only letters and digits');
                        $request->session()->flash('message', 'Username can have only letters and digits');
                        return redirect('/login');
                    }
                }
            }
        } else if ($request->isMethod('GET')) {
            return view('login');
        }
        return redirect('/login');
    }

    public function logOut(Request $request) {
        Auth::logout();
        $request->session()->flush();
        Log::error($request->input('username') . ' logged out');
        $request->session()->flash('message', 'You logged out');
        return redirect('/login');
    }

    public function signUp(Request $request) {
        if($request->isMethod('POST')) {
            $credentials = $request->all();
            if(isset($credentials['username']) && isset($credentials['password'])) {
                if(strlen($credentials['username']) < 1 || strlen($credentials['password']) < 1) {
                    Log::error('Username and password are required');
                    $request->session()->flash('message', 'Username and password are required');
                    return redirect('/signup');
                } else {
                    if(preg_match('/^(?=.{1,20}$)[a-zA-Z0-9]+$/', $credentials['username']) && strlen($credentials['password']) > 8) {
                        $user = DB::select('select user_id from users where username = ?', [$credentials['username']]);
                        if(count($user) === 1) {
                            Log::error('Sign up fail' . $credentials['username'] . ' ' . $credentials['password']);
                            $request->session()->flash('message', 'User already exists');
                            return redirect('/signup');
                        } else {
                            $hashed_pass = Hash::make($credentials['password']);
                            DB::insert('insert into users (username, password) values (?, ?)', [$credentials['username'], $hashed_pass]);
                            Log::info('Sign up success '. $credentials['username']);
                            $request->session()->flash('message', 'You are signed up');
                            return redirect('/login');
                        }
                    } else {
                        Log::error('Username can have only letters and digits');
                        $request->session()->flash('message', 'Username can have only letters and digits. Password should be more than 8 chars');
                        return redirect('/signup');
                    }
                }
            }
        } else if ($request->isMethod('GET')) {
            return view('signup');
        }
        return redirect('/signup');
    }
}
