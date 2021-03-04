<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Ticket;

use Illuminate\Http\Request;

class AjaxController extends Controller
{

    function ajax() {
        return response()->json(['ajax' => 'respuesta', 'datos' => [rand(1,10), rand(1,10), rand(1,10)]]);
    }

    function moneda() {
        //como esto es ajax, sólo obtengo la vista sin datos adicionales
        return view('ajax.moneda');
    }
    
    // function empresaajax() {
    //     //return response()->json(['data' => Enterprise::all()]);
    //     //return response()->json(['data' => Enterprise::paginate(3)]);
    //     return response()->json(Enterprise::paginate(3));
    // }
    
    // function empresaajaxid($id) {
    //     $token = csrf_token();
    //     $enterprise = Enterprise::find($id);
    //     return response()->json(['enterprise' => $enterprise, 'token' => $token]);
    // }

    function estandar() {
        $datos = [];//eloquent, db, pdo
        return response()->json($datos);//json_encode() csrf method url
    }

    private function getRows(Request $request) {
        $rows = 3;
        if($request->input('rows') != null && is_numeric($request->input('rows'))) {
            $rows = $request->input('rows');
        }
        return $rows;
    }
    
    private function getOrder(Request $request) {
        $response = [];
        $order = ['name', 'phone', 'contactperson', 'taxnumber', 'id'];
        $orderby = $request->input('orderby');
        $response['orderby'] = $orderby;
        $sort = 'asc';
        if($orderby != null) {
            if(!isset($order[$orderby])) {
                $orderby = 0;
            }
            $orderbyField = $order[$orderby];
            if($request->input('sort') != null) {
                $sort = $request->input('sort');
                if(!($sort == 'asc' || $sort == 'desc')) {
                    $sort = 'asc';
                }
            }
            $response['field'] = $orderbyField;
            $response['sort'] = $sort;
            $response['orderby'] = $orderby;
        }
        return $response;
    }
    
    private function getParameters(Request $request) {
        $order = $this->getOrder($request);
        $rows = $this->getRows($request);;
        $search = $request->input('search');
        return array_merge($order, ['rows' => $rows, 'search' => $search]);
    }
    
    function ajax2(Request $request) {
        $parameters = $this->getParameters($request);
        $enterprises = new Enterprise();
        if($parameters['search'] != null) {
            $search = $parameters['search'];
            $enterprises = $enterprises->where('name', 'like', '%' . $search . '%')
                                        ->orWhere('contactperson', 'like', '%' . $search . '%')
                                        ->orWhere('taxnumber', 'like', '%' . $search . '%')
                                        ->orWhere('phone', 'like', '%' . $search . '%');
        }
        if(isset($parameters['field'])) {
            $enterprises = $enterprises->orderby($parameters['field'], $parameters['sort']);
            unset($parameters['field']);
        }
        $enterprises = $enterprises->orderBy('name', 'asc')->paginate($parameters['rows'])->appends($parameters);
        return response()->json(array_merge(['enterprises' => $enterprises], $parameters));
    }
}