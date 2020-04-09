@extends('layouts.app')

@section('contentheader_title')
    {!! $title !!}
{{--    {{ dd($errors) }}--}}
@endsection

@section('main-content')
    <form class="form-horizontal" method="post" action="{{ action('AccountController@postChangePassword', ['id' => $data->id]) }}" enctype="multipart/form-data">
        <div class="box box-primary">

            <div class="box-body">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('old_password') ? 'has-error' : '' }}">
                    <label class="col-md-2 control-label">Vecchia Password</label>
                    <div class="col-md-6">
                        <input type="password" name="old_password" class="form-control" placeholder="Vecchia Password" />
                    </div>
                    @if ($errors->has('old_password'))
                        <span class="help-block">{{ $errors->first('old_password') }}</span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label class="col-md-2 control-label">Nuova Password</label>
                    <div class="col-md-6">
                        <input type="password" name="password" class="form-control" placeholder="Nuova Password" />
                    </div>

                    @if ($errors->has('password'))
                        <span class="help-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <label class="col-md-2 control-label">Conferma Password</label>
                    <div class="col-md-6">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Conferma Password" />
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

                <span class="help-block col-md-offset-2" style="padding: 0 5px">
                    La password deve contenere <strong>un carattere Maiuscolo, uno Minuscolo, un Numero e un carattere Speciale</strong>.<br>
                    Lunghezza MINIMO 8 , MASSIMO 16<br>
                    Caratteri ammessi LETTERE, NUMERI &nbsp;&nbsp;£&nbsp;&nbsp;$&nbsp;&nbsp;%&nbsp;&nbsp;&&nbsp;&nbsp;!&nbsp;&nbsp;?&nbsp;&nbsp;:&nbsp;&nbsp;.&nbsp;&nbsp;-&nbsp;&nbsp;_
                </span>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right text-bold">{!! trans('messages.button.save') !!}</button>
            </div>
        </div>
    </form>
@stop