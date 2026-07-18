<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\P2pOffer;

class P2pOfferController extends Controller
{
    public function index()
    {
        $offers = P2pOffer::with('maker')->latest()->paginate(15);

        return view('admin.p2p-offers.index', compact('offers'));
    }

    public function show(P2pOffer $p2pOffer)
    {
        return view('admin.p2p-offers.show', ['offer' => $p2pOffer]);
    }
}
