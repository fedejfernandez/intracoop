<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluacionDesempeno;
use Illuminate\Http\Request;

class EvaluacionDesempenoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.evaluaciones-desempeno.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.evaluaciones-desempeno.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.evaluaciones-desempeno.index')->with('success', 'Evaluación creada (placeholder).');
    }

    /**
     * Display the specified resource.
     */
    public function show(EvaluacionDesempeno $evaluacionDesempeno)
    {
        return view('admin.evaluaciones-desempeno.show', compact('evaluacionDesempeno'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EvaluacionDesempeno $evaluacionDesempeno)
    {
        return view('admin.evaluaciones-desempeno.edit', compact('evaluacionDesempeno'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EvaluacionDesempeno $evaluacionDesempeno)
    {
        return redirect()->route('admin.evaluaciones-desempeno.index')->with('success', 'Evaluación actualizada (placeholder).');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EvaluacionDesempeno $evaluacionDesempeno)
    {
        //
    }
}
