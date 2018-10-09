<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use Auth;
use File;
use Illuminate\Support\Facades\Storage;

class UpdateUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        return view('board.profile');
    }

    protected function update(Request $data)
    {
        $this->validate($data, [
            'name' => 'required',
            'lastName' => 'required',
            'country' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'dateOfBirth' => 'required',
            'photo' => 'image|max:1000',
        ]);

        $user = User::find(Auth::id());
        $photo = $user->photo;
        $user->update($data->all());
        $user->save();
        if ($data->hasFile('photo')){
            if (isset($photo)){
                Storage::delete("/public/".$photo);
            }
            $path = $data->photo->store('public');
            $user->photo=  basename($path);
            $user->save();
        }
        return back();
    }

    public function updateUsage(Request $data)
    {
      $user = User::find(Auth::id());
      $user->usage=$user->usage+$data->input('iter');
      $user->save();
    }

}
