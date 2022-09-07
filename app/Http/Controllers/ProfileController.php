<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class ProfileController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */ 
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile/profile');
    }

    public function update(Request $request)
    {
            $type = $request->type;
            $value = $request->val;
            $userEmail = Auth::user()->email; 
            $user = User::where('email',$userEmail)->first();
            switch($type){
                case 'name':
                    $user->name = $value;
                    if($user->save())
                    {
                        return redirect()->back()->with('success','success');
                    }
                    else
                    {
                        return redirect()->back()->with('error','error');
                    }
                break;

                case 'email':
                    $user->email = $value;
                    if($user->save())
                    {
                        return redirect()->back()->with('success','success');
                    }
                    else
                    {
                        return redirect()->back()->with('error','error');
                    }
                break;

                case 'passwd':
                    $user->password = bcrypt($value);
                    if($user->save())
                    {
                        return redirect()->back()->with('success','success');
                    }
                    else
                    {
                        return redirect()->back()->with('error','error');
                    }
                break;

                default:

                break;
            }
    }
}
