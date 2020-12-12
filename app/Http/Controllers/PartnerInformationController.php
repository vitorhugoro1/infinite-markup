<?php

namespace App\Http\Controllers;

use App\Models\Partner;
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
        return view('partner-information.create', [
            'partners' => Partner::all(['id', 'name'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|file|mimes:xml',
            'partner' => 'required|integer',
            'async' => 'required|boolean'
        ]);

        return redirect('partner-information.index');
    }
}
