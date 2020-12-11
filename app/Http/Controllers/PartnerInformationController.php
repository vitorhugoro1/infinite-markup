<?php

namespace App\Http\Controllers;

use App\Models\PartnerInformation;
use Illuminate\Http\Request;

class PartnerInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('partner-information.index', [
            'informations' => PartnerInformation::where('user_id', auth()->user())->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnerInformation  $partnerInformation
     * @return \Illuminate\Http\Response
     */
    public function show(PartnerInformation $partnerInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerInformation  $partnerInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(PartnerInformation $partnerInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnerInformation  $partnerInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartnerInformation $partnerInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerInformation  $partnerInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnerInformation $partnerInformation)
    {
        //
    }
}
