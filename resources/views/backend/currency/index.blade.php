@extends('backend.base')

@section('postscript')
<script src="{{ url('assets/backend/js/script.js?r=' . uniqid()) }}"></script>
@endsection

@section('content')


@if(session()->has('op'))
<div class="alert alert-success" role="alert">
  Operation: {{ session()->get('op') }}. Id: {{ session()->get('id') }}. Result: {{ session()->get('r') }}
</div>
<br>
@endif

<table class="table table-hover">
    <thead>
        <tr>
        <th scope="col">id #</th>
        <th scope="col">name</th>
        <th scope="col">symbol</th>
        <th scope="col">country</th>
        <th scope="col">change</th>
        <th scope="col">created</th>
        <th scope="col">show</th>
        <th scope="col">edit</th>
        <th scope="col">delete</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($currencies as $currency)
        <tr>
            <td scope="row">{{ $currency->id }}</td>
            <td>{{ $currency->name }}</td>
            <td>{{ $currency->symbol }}</td>
            <td>{{ $currency->country }}</td>
            <td>{{ $currency->change }}</td>
            <td>{{ $currency->created }}</td>
            
            <td><a href="{{ url('backend/currency/' . $currency->id) }}">show</a></td>
            <td><a href="{{ url('backend/currency/' . $currency->id . '/edit') }}">edit</a></td>
            <td><a data-id="{{ $currency->id }}" data-name="{{ $currency->name }}" class="enlaceBorrar" href="#">delete</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="card-footer">
    <a href="{{ url('backend/currency/create') }}" class="btn btn-primary">Add currency</a>
</div>

<form id="formDelete" action="{{ url('backend/currency') }}" method="post">
    @method('delete')
    @csrf
</form>
@endsection