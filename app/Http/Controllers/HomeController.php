<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $applications = auth()->user()->applications()->with(['course', 'payments'])->latest()->get();
        return view('home', compact('applications'));
    }
    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Illuminate\Support\Facades\Hash::check($value, auth()->user()->password)) {
                    $fail('Your current password does not match our records.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return redirect()->route('home')->with('status', 'Password successfully updated');
    }
}
