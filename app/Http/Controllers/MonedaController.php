<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Moneda::all();
        return view('backend.currency.index', ['currencies' => $currencies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.currency.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currency = new Moneda($request->all());
        try {
            $result = $currency->save();    
        } catch(\Exception $e) {
            $result = 0;
        }
        
        if($currency -> id > 0) {
            $response = ['op' => 'store', 'r' => $result, 'id' => $currency->id];
            return redirect('backend/currency')->with($response);
        } else {
            return back() -> withInput()->with(['error' => 'algo ha fallado']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function show(Moneda $moneda, $id)
    {
        $moneda = Moneda::find($id);
        return view('backend.currency.show', ['currency' => $moneda]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function edit(Moneda $moneda, $id)
    {
        $moneda = Moneda::find($id);
        return view('backend.currency.edit', ['currency' => $moneda]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Moneda $moneda, $id)
    {
        $moneda = Moneda::find($id);
        try {
            $result = $moneda->update($request->all());
        } catch(\Exception $e) {
            $result = 0;
        }
        
        if($result > 0) {
            $response = ['op' => 'update', 'r' => $result, 'id' => $moneda->id];
            return redirect('backend/currency')->with($response);
        } else {
            return back() -> withInput()->with(['error' => 'algo ha fallado']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Moneda $moneda, $id)
    {
        $moneda = Moneda::find($id);
        $id = $moneda->id;
        try {
            $result = $moneda->delete();
        } catch(\Exception $e) {
            $result = 0;
        }
        
        $response = ['op' => 'destroy', 'r' => $result, 'id' => $id];
        return redirect('backend/currency')->with($response);
    }
}
