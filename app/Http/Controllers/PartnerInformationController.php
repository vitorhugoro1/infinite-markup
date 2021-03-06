<?php

namespace App\Http\Controllers;

use App\Actions\ProcessMarkupFileData;
use App\Actions\StorePartnerInformation;
use App\Models\Partner;
use App\Models\PartnerInformation;
use Illuminate\Http\Request;
use App\Http\Requests\StorePartnerInformationRequest;

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
            'informations' => PartnerInformation::where('user_id', auth()->id())->paginate()
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
    public function store(
        StorePartnerInformationRequest $request,
        StorePartnerInformation $storePartnerInformation,
        ProcessMarkupFileData $processMarkupFileData
    ) {
        $partnerInformation = $storePartnerInformation->store($request->validated(), $request->user());

        if ($request->get('async')) {
            $processMarkupFileData->onQueue()->execute($partnerInformation);
        }

        if (!$request->get('async')) {
            $processMarkupFileData->execute($partnerInformation);
        }

        return redirect()->route('partner-information.index');
    }
}
