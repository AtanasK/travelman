<?php

namespace App\Http\Controllers;

use App\Location;
use App\User;
use Illuminate\Http\Request;


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
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Location $location)
    {
        return $location;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {

        $this->validate($request, [
            'destination' => 'required',
        ]);
        $user->addLocation(request('destination'));

        return response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Location $location)
    {
        $this->validate(request(), [
            'destination' => 'required',
            'completed' => 'required'
        ]);

        return response();

        //$location->destination = request('destination');
        //$location->completed = request('completed');

        //$location->save();

        //return response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }
}
