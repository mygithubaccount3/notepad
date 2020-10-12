<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


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
        $value = $request->session()->get('error');
        if($request->isMethod('POST')) {
            $username = $request->input('username');
            $password = $request->input('password');
            if(isset($username) && isset($password)) {
                if(strlen($username) < 1 || strlen($password) < 1) {
                    Log::error('Username and password are required');
                    $request->session()->flash('error', 'Username and password are required');
                    return redirect('/login');
                } else {
                    if(preg_match('/^(?=.{1,20}$)[a-zA-Z0-9]+$/', $username)) {
                        $check = hash('md5', "XyZzy12*_$password");
                        $user = DB::select('SELECT user_id FROM user WHERE username = ? AND password = ?', [$username, $password]);
                        if (isset($user[0])) {
                            /*echo $user[0]->username;*/
                            Log::info('Login success '. $username);
                            $request->session()->put('user_id', $user[0].$username);
                            return redirect('/');
                        } else {
                            Log::error("Login fail $username $check");
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
    }
}
