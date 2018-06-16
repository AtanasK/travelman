<?php

namespace App\Http\Controllers;

use App\Avatar;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->validate($request, [
            'avatar' => 'required|mimes:jpeg,png'
        ]);

        $file = $request->file('avatar');
        $path = $file->store('local');

        $oldAvatar = $user->avatar()->latest()->first();

        $newAvatar = new Avatar();
        $newAvatar->path = $path;

        if ($user->avatar()->save($newAvatar)) {
            if ($oldAvatar) {
                $oldAvatar->delete();
                Storage::delete($oldAvatar->path);
            }
            return response()->json(['sucess' => 'true'], 201);
        }
        return response()->json(['sucess' => 'false'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $avatar = $user->avatar()->latest()->first();
        if ($avatar)
            $contents = Storage::get($avatar->path);

        return response()->make($contents, 200, [
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($contents)
        ]);
    }

}
