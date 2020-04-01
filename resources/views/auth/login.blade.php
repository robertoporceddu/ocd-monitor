@extends('auth.app')

@section('main-content')
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><img src="{{ asset('/media/swiss_army_knife.png') }}" class="img img-responsive" width="128" style="margin: 0 auto;"/></a>
            <small>Octopus Swiss Army Knife</small>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('auth.title_login') }}</p>

            <form action="{{ url('/login') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input autofocus="autofocus" id="email" type="email" class="form-control" name="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label class="">
                                <input type="checkbox" name="remember" class="minimal">
                                {{ trans('auth.remember') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('auth.login') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <a href="{{ url('/password/reset') }}">{{ trans('auth.forgot') }}</a>

        </div>
        <!-- /.login-box-body -->
    </div>
@endsection
