<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AjaxMonedaController extends Controller
{
    public function index(Request $request)
    {
        $token = csrf_token();
        $monedas = $this->getPage($request, $request->input('page'));
        return response()->json(['monedas' => Moneda::paginate(3), 'token' => $token]);
    }
    
    private function getPage(Request $request, $page = 1) {
        $currentPage = $page;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $monedas = Moneda::paginate(3);
        $page = $monedas->currentPage();
        $lastPage = $monedas->lastPage();
        if($page > $lastPage) {
            //$request->merge(['page' => $lastPage]);
            $currentPage = $lastPage;
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $monedas = Moneda::paginate(3);
        }
        return $monedas;
    }

    public function store(Request $request)
    {
        $moneda = new Moneda($request->all());
        try {
            $result = $moneda->save();
        } catch(\Exception $e) {
            $result = false;
        }
        if($moneda->id > 0) {
            $response = ['r' => $result, 'id' => $moneda->id];
            $response = ['moneda' => $moneda];
            return response()->json($response);
            
        } else {
            return response()->json(['error' => 'algo ha fallado']);
        }
    }

    public function show($id)
    {
        //$token = csrf_token();
        $moneda = Moneda::find($id);
        return response()->json(['moneda' => $moneda]);
    }

    public function update(Request $request, $id)
    {
        $moneda = Moneda::find($id);
        try {
            $result = $moneda->update($request->all());
        } catch (\Exception $e) {
            $result = false;
        }
        return response()->json(['result' => $result, 'moneda' => $moneda]);
    }

    public function destroy(Request $request, $id)
    {
        $monedaa = Moneda::find($id);
        try {
            $result = $monedaa->delete(); //Moneda::destroy($id);
        } catch(\Exception $e) {
            $result = 0;
        }
        $monedas = $this->getPage($request, $request->input('_page'));
        $monedas->setPath(url('ajaxmoneda'));
        return response()->json(['monedas' => Moneda::paginate(3), 'result' => $result]);
    }
}