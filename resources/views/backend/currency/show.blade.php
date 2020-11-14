@extends('backend.base')

@section('postscript')
<script src="{{ url('assets/backend/js/script.js?r=' . uniqid()) }}"></script>
@endsection

@section('content')
<form id="formDelete" action="{{ url('backend/currency/' . $currency->id) }}" method="post">
    @method('delete')
    @csrf
</form>
<div class="card-footer">
    <a href="{{ url('backend/currency') }}" class="btn btn-primary">Back</a>
    <a href="{{ url('backend/currency/create') }}" class="btn btn-primary">Add currency</a>
    <a href="{{ url('backend/currency/' . $currency->id . '/edit') }}" class="btn btn-primary">Edit currency</a>
    <a href="#" id="enlaceBorrar" data-id="{{ $currency->id }}" data-name="{{ $currency->name }}" class="btn btn-danger">Delete currency</a>
</div>


<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Field</th>
            <th scope="col">Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Name</td>
            <td>{{ $currency->name }}</td>
        </tr>
        
        <tr>
            <td>Symbol</td>
            <td>{{ $currency['symbol'] }}</td>
        </tr>
        
        <tr>
            <td>Country</td>
            <td>{{ $currency['country'] }}</td>
        </tr>
        
        <tr>
            <td>Change</td>
            <td>{{ $currency['change'] }}</td>
        </tr>
        
        <tr>
            <td>Created</td>
            <td>{{ $currency['created'] }}</td>
        </tr>
    </tbody>
</table>
<!--<img src="{{--url('logo/' . $currency->id)--}}">-->
@endsection