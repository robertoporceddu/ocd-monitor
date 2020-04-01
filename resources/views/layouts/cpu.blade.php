<!DOCTYPE html>
<html lang="en">

@extends('layouts.app')

@section('content-octopus')
    <div class="col-md-8">
        @include('layouts.partials.cpu')
    </div>
    <div class="col-md-4">

        @include('layouts.partials.log')
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <p id="msg"></p>
            <button class="btn btn-primary" onclick="reloadServer()"> Riavvia Server </button>
        </div>
    </div>

@endsection