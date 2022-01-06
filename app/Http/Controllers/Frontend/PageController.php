<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;

class PageController extends Controller
{
    public function home(){
        return view('frontend.home');
    }

    public function profile(){
        $user = Auth::guard('web')->user();
        return view('frontend.profile', compact('user'));
    }

    public function updatePassword(){
        return view('frontend.update_password');
    }

    public function updatePasswordStore(UpdatePassword $request){

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $user =Auth::guard('web')->user();

        if (Hash::check($old_password, $user->password)){
            $user->password = Hash::make($new_password);
            $user->update();

            return redirect()->route('profile')->with('update','Successfully Updated.');
        }

        return back()->withErrors(['old_password'=>'The old password is not correct!'])->withInput();
    }
}
