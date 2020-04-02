@extends('layouts.app')

@section('$contentheader_description')
@endsection

@section('main-content')

<div class="row">
    <div class="col-sm-12 text-center">
        <h1 class="text-yellow" style="font-size: 150px; margin: 0"><i class="fa fa-warning text-yellow"></i> 403!</h1>
        <h2 style="font-size: 100px">{!! trans('errors.403') !!}</h2>
    </div>
</div>

@endsection