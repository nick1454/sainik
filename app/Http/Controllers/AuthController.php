<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    public function form()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if (Auth::attempt($request->only('email','password'))) {

                    if ($user->role->name == 'admin') {
                        return redirect()->route("admin.dashboard");
                    }

                    if ($user->role->name == 'student') {
                        return redirect()->route("student.dashboard");
                    }
                }
            }

            return back()->with('error','Wrong Password.');
        }

        return back()->with('error','No User Found.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    public function changePassword(Request $request)
    {
        if (strtolower($request->method()) == 'get') {
            return view('change_password');
        }

        $request->validate([
            'password' => ['required'],
            'con_pass' => ['required'],
            'new_pass' => ['required','min:8','max:32'],
        ]);

        if ($request->password !== $request->con_pass) {
            return back()->with('error','Password and Confirm password does not match.');
        }

        if (!auth()->user()->update([
            'new_pass' => Hash::make($request->password)
        ])) {
            return back()->with('error','Unable to update.');
        }

        return back()->with('success','Password Updated.');
    }

    public function apiLogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';

        if (!$user) {
            return [
                'message' =>'user not found',
                'data' => [],
                'status' => '404',
            ];
        }

        if (!Hash::check($password, $user->password)) {
            return [
               'message' =>'password does not match',
                'data' => [],
               'status' => '404',
            ];
        }

        $token = $this->generateRandomString('1234567890abcdefghijklmnopqrstuvwxyz',32);

        $user->update([
            'token' => $token
        ]);

        return [
            'message' => 'login success',
            'data' => ['user' => $user],
            'status' => '200',
        ];
    }

    public function authUser(Request $request) {
        return Auth::user();
    }

    private function generateRandomString($inputString, $length) {
        $randomString = '';
        $charactersLength = strlen($inputString);
        for ($i = 0; $i < $length; $i++) {
            $randomCharacter = $inputString[rand(0, $charactersLength - 1)];
            $randomString .= $randomCharacter;
        }
        return $randomString;
    }
}
