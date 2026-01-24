<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function landlordListings()
    {
        return response()->json(['message' => 'Landlord listings']);
    }
}