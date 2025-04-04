<?php

namespace App\Http\Controllers;

use App\Models\KPIs;
use Illuminate\Http\Request;

class KPIsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kPIs= KPIs::all();

    if (request()->wantsJson()) {
        return response()->json($kPIs);
    }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kpi = KPIs::create($request->validated());
        $response = [
            'message' => 'KPI created successfully!',
            'data' => $kpi
        ];
        if ($request->wantsJson()) {
            return response()->json($response, 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KPIs $kPIs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KPIs $kPIs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KPIs $kPIs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KPIs $kPIs)
    {
        //
    }
}
