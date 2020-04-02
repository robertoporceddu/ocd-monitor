@extends('auth.app')

@section('main-content')
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><img src="{{ asset('/media/swiss_army_knife.png') }}" class="img img-responsive" width=""/></a>
            <small>Octopus Swiss Army Knife</small>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{!! trans('auth.title_reset') !!}</p>

            <form class="" role="form" method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}" />
                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('auth.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="{{ trans('auth.confirm') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password_confirmation'))
                        <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary col-md-12">
                        <i class="fa fa-btn fa-refresh"></i> {{ trans('auth.reset') }}
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
@endsection
