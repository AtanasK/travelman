<?php

namespace App\Http\Controllers;

use App\Location;
use App\User;
use Illuminate\Http\Request;
use \Validator;


class LocationController extends Controller
{
    /**
     * Function for X
     * @param User $user
     * @return array of locations
     */
    public function index(User $user)
    {
        //
        $locations = $user->locations;

        return $locations;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Location $location)
    {
        return response()->json($location, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'destination' => 'required|string|min:2|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->addLocation($request->json('destination'));

        return response()->json(['status' => "success"], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Location $location)
    {

        $this->validate($request, [
            'destination' => 'required|string|min:2|max:50',
            'completed' => 'required|boolean'
        ]);

        $location->destination = $request->json('destination');
        $location->completed = $request->json('completed');

        $location->save();

        return response()->json(['status' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $success = false;
        try {
            $success = $location->delete();
            $status = $success ? 'success' : 'failed';
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }
        return response()->json(['status' => $status], $success ? 200 : 400);
    }
}
