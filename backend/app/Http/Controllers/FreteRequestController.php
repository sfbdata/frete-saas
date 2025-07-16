<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreteRequest;

class FreteRequestController extends Controller
{
    public function store(Request $request)
    {
        $fields = $request->validate([
            'origin'         => 'required|string',
            'destination'    => 'required|string',
            'truck_type'     => 'required|string',
            'needs_helper'   => 'required|boolean',
            'has_stairs'     => 'required|boolean',
        ]);

        $frete = FreteRequest::create($fields);

        return response()->json([
            'message' => 'Solicitação de frete registrada com sucesso.',
            'data' => $frete
        ], 201);
    }
}
