@extends('auth.app')

<!-- Main Content -->
@section('main-content')
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><img src="{{ asset('/media/swiss_army_knife.png') }}" class="img img-responsive" width=""/></a>
            <small>Octopus Swiss Army Knife</small>
        </div>
        <!-- /.login-logo -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="login-box-body">
            <p class="login-box-msg">{!! trans('auth.title_reset') !!}</p>

            <form class="" role="form" method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}
                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('auth.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary col-md-12">
                        <i class="fa fa-btn fa-envelope"></i> {{ trans('auth.reset_link') }}
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>

            <br />

            <div class="row">
                <div class="col-md-12">
                    <h4>{{ app('request')->session()->get('status') }}</h4>
                </div>
            </div>
        </div>
    </div>
@endsection
