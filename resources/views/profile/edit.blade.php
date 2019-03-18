@extends('layouts.app')
@section('content')
@section('content')
<div class="container">
  <form method="POST" action="/profiles/{{$profile->id}}" enctype="multipart/form-data">
    {{ method_field('PATCH') }}
    {{ csrf_field() }}
    <div class="form-group">
    name  <input type="text" name="name" value="{{ $profile->name }}" />
    </div>
    <div class="form-group">
     address  <input type="textarea" name="address" value="{{ $profile->address }}" />
    </div> 
    <div class="form-group">
    @if ("/storage/images/{{ $profile->images }}")
        <img src="{{ $profile->image }}">
    @else
            <p>No image found</p>
    @endif
        image <input type="file" name="image" value="{{ $profile->images }}"/>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    
</div>
 @endsection
@endsection