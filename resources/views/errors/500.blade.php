@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.servererror') }}
@endsection

@section('contentheader_title')
    {{ trans('adminlte_lang::message.500error') }}
@endsection

@section('$contentheader_description')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-12 text-center">
            <h1 class="text-yellow" style="font-size: 150px; margin: 0"><i class="fa fa-chain-broken text-yellow"></i> 500!</h1>
            <h2 style="font-size: 100px">{!! trans('errors.500') !!}</h2>
        </div>
    </div>
@endsection