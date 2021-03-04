@extends('backend.base')

@section('modalBodyEdit')
<!--<input type="hidden" class="form-control" id="_method" name="_method" value="">-->
<!--<input type="hidden" class="form-control" id="_route" name="_route" value="">-->
<form id="monedaForm">
    <div class="card-body">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" maxlength="60" minlength="2" required class="form-control" id="name" placeholder="Nombre de la moneda" name="name" value="">
        </div>
        <div class="form-group">
            <label for="symbol">Symbol</label>
            <input type="text" maxlength="15" minlength="9" required class="form-control" id="symbol" placeholder="Símbolo" name="symbol" value="">
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" maxlength="100" minlength="2" required class="form-control" id="country" placeholder="País" name="country" value="">
        </div>
        <div class="form-group">
            <label for="change">Change</label>
            <input type="number" maxlength="15" minlength="9" required class="form-control" id="change" placeholder="Change" name="change" value="">
            <!--<input type="hidden" required class="form-control" id="_token" name="_token" value="">-->
        </div>
    </div>
</form>
@endsection

@section('modalBodyAdd')
<!--<input type="hidden" class="form-control" id="_methodAdd" name="_method" value="post">-->
<!--<input type="hidden" class="form-control" id="_routeAdd" name="_route" value="{{ url('ajaxmoneda') }}">-->
<form id="addMonedaForm">
    <div class="card-body">
        <div class="form-group">
            <label for="nameAdd">Name</label>
            <input type="text" maxlength="60" minlength="2" required class="form-control" id="nameAdd" placeholder="Nombre de la moneda" name="name" value="">
        </div>
        <div class="form-group">
            <label for="symbolAdd">Symbol</label>
            <input type="text" maxlength="15" minlength="9" required class="form-control" id="symbolAdd" placeholder="Símbolo" name="symbol" value="">
        </div>
        <div class="form-group">
            <label for="countryAdd">Country</label>
            <input type="text" maxlength="100" minlength="2" required class="form-control" id="countryAdd" placeholder="País" name="country" value="">
        </div>
        <div class="form-group">
            <label for="changeAdd">Change</label>
            <input type="number" maxlength="15" minlength="9" required class="form-control" id="changeAdd" placeholder="Change" name="change" value="">
        </div>
    </div>
</form>
@endsection

@section('modalBodyDelete')    
<div class="card-body">
    <div class="form-group">
        ¿Seguro que quieres borrar la moneda <span id="nameBorrar"></span>?
    </div>
</div>
@endsection

@section('modalBody')
    <h1>Body 2</h1>
@endsection

@section('modal')
    @include('ajax.include.modal', ['id' => 'edit', 'title' => 'Editar moneda', 'actionButton' => 'Guardar moneda', 'modalBody' => 'modalBodyEdit'])
    @include('ajax.include.modal', ['id' => 'add', 'title' => 'Añadir moneda', 'actionButton' => 'Añadir moneda', 'modalBody' => 'modalBodyAdd'])
    @include('ajax.include.modal', ['id' => 'delete', 'title' => 'Borrar moneda', 'actionButton' => 'Borrar moneda', 'modalBody' => 'modalBodyDelete'])
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                <a href="javascript: void(0)" data-toggle="modal" data-target="#addModal" class="btn btn-primary">Añadir moneda</a>
            </div>
        </div>
    </div>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>
                #id
                <a href="#">↓</a>
                <a href="#">↑</a>
            </th>
            <th>
                name
                <a href="#">↓</a>
                <a href="#">↑</a>
            </th>
            <th>
                symbol
                <a href="#">↓</a>
                <a href="#">↑</a>
            </th>
            <th>
                country
                <a href="#">↓</a>
                <a href="#">↑</a>
            </th>
            <th>
                change
                <a href="#">↓</a>
                <a href="#">↑</a>
            </th>
            <!--<th>show</th>-->
            <th>edit</th>
            <th>delete</th>
        </tr>
    </thead>
    <tbody id="tbody">
    </tbody>
</table>
<div class="row">
    <div class="col-lg-6" >
        <nav>
           <ul class="pagination" id="enlacesPaginacion">
           </ul>
        </nav>  
    </div>
    <div class="col-lg-5">
        <div class="float-right">
            <label for="selectRows">Rows in each page: </label>
            <select name="rows" class="form-control" id="selectRows">
                <option >2</option>
                <option >3</option>
                <option >5</option>
                <option selected>10</option>
            </select>
        </div>
    </div>
</div>
@endsection

@section('poststyle')
<link rel="stylesheet" href="{{ url('assets/backend/css/moneda.css') }}">
@endsection

@section('postscript')
<script src="{{ url('assets/backend/js/moneda.js?=' . uniqid()) }}"></script>
@endsection