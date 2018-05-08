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
     * @return \App\Location
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
            'lat' => 'required|string',
            'lng' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->addLocation(
            $request->json('lat'),
            $request->json('lng'),
            $request->json('completed', 0)
        );

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
        $validator = Validator::make($request->all(), [
            'lat' => 'required|string',
            'lng' => 'required|string',
            'completed' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $location->lat = $request->json('lat');
        $location->lng = $request->json('lng');
        $location->completed = $request->json('completed');

        $location->save();

        return response()->json(['status' => "success"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Location $location)
    {
        try {
            if ($location->delete()) {
                return response()->json(['status' => "success", 200]);
            }
            return response()->json(['status' => "aw shiet"], 400);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getMessage()], 500);
        }
    }
}
