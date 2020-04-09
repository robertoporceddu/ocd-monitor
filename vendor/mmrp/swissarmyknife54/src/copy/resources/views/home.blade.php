@extends('layouts.app')

@section('contentheader_title')
    {!! $title !!}
@endsection

{{--@section('main-content')--}}
{{--<div class="row">--}}
    {{--@if(isset($leads['total']))--}}
    {{--<div class="col-md-3 ">--}}
        {{--<div class="small-box bg-primary">--}}
            {{--<div class="inner">--}}
                {{--<h3>{{ $leads['total'] }}</h3>--}}
                {{--<h4>{{ trans('lead.leads') }}</h4>--}}
            {{--</div>--}}
            {{--<div class="icon">--}}
                {{--<i class="fa fa-address-book"></i>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--@endif--}}

    {{--@if(isset($leads['available']))--}}
    {{--<div class="col-md-3 ">--}}
        {{--<div class="small-box bg-green-active">--}}
            {{--<div class="inner">--}}
                {{--<h3>{{ $leads['available'] }}</h3>--}}
                {{--<h4>{{ trans('profiling.avail') }}</h4>--}}
            {{--</div>--}}
            {{--<div class="icon">--}}
                {{--<i class="fa fa-address-book"></i>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--@endif--}}

    {{--@if(isset($leads['onrun']))--}}
    {{--<div class="col-md-3 ">--}}
        {{--<div class="small-box bg-red-active">--}}
            {{--<div class="inner">--}}
                {{--<h3>{{ $leads['onrun'] }}</h3>--}}
                {{--<h4>{{ trans('profiling.onrun') }}</h4>--}}
            {{--</div>--}}
            {{--<div class="icon">--}}
                {{--<i class="fa fa-address-book"></i>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--@endif--}}
{{--</div>--}}
{{--@endsection--}}
