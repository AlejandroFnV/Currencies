@extends('backend.base')

@section('postscript')
<script src="{{ url('assets/backend/js/script.js?r=' . uniqid()) }}"></script>
@endsection

@section('content')
<div class="card-footer">
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
</div>


@if(Session::get('error') != null)
  <h2>{{ Session::get('error') }}</h2>
@endif

<form role="form" action="{{ url('backend/currency') }}" method="post" id="addCurrencyForm">
    @csrf
    <div class="card-body">
        
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" maxlength="60" minlength="2" required class="form-control" id="name" placeholder="Currency name" name="name" value="{{ old('name') }}">
      </div>
      
      <div class="form-group">
        <label for="symbol">Symbol</label>
        <input type="text" maxlength="5" minlength="1" required class="form-control" id="symbol" placeholder="Symbol" name="symbol" value="{{ old('symbol') }}">
      </div>
      
      <div class="form-group">
        <label for="country">Country</label>
        <input type="text" maxlength="100" minlength="2" required class="form-control" id="country" placeholder="Country" name="country" value="{{ old('country') }}">
      </div>
      
      <div class="form-group">
        <label for="change">Change</label>
        <input type="number" min="0.0001" max="99.9" step="0.0001" required class="form-control" id="change" placeholder="Change" name="change" value="{{ old('change') }}">
      </div>
      
      <div class="form-group">
        <label for="created">Created at</label>
        <input type="date" class="form-control" id="created" name="created" value="{{ date('Y-m-d') }}">
      </div>
    </div>
    
    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

@endsection