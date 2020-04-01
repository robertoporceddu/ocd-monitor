<form class="form-horizontal" method="post" action="{{ action($action . '@upload', $parameters) }}" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
        <label class="col-md-2 control-label">{!! trans('batch.file') !!}</label>
        <div class="col-md-6">
            <input type="file" class="form-control" name="file" id="file" placeholder="{!! trans('batch.file') !!}" >
            @if ($errors->has('file'))
                <span class="help-block">{{ $errors->first('file') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('delimiter') ? 'has-error' : '' }}">
        <label class="col-md-2 control-label">{!! trans('batch.delimiter') !!}</label>
        <div class="col-md-6">
            <select name="delimiter" id="delimiter" class="form-control">
                <option value="">--</option>
                <option value=",">,</option>
                <option value=";">;</option>
                <option value="|">|</option>
                <option value="\t">TAB</option>
            </select>
            <span class="help-block">Solo per file CSV</span>
            @if ($errors->has('delimiter'))
                <span class="help-block">{{ $errors->first('delimiter') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('header') ? 'has-error' : '' }}">
        <label class="col-md-2 control-label">{!! trans('batch.header') !!}</label>
        <div class="col-md-6">
            <input type="checkbox" class="minimal" name="header" id="header" placeholder="{!! trans('batch.header') !!}" value="1">
            @if ($errors->has('header'))
                <span class="help-block">{{ $errors->first('header') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <label class="col-md-2 control-label">{!! trans('batch.name') !!}</label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="name" id="name" placeholder="{!! trans('batch.name') !!}" value="">
            @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
        <label class="col-md-2 control-label">{!! trans('batch.description') !!}</label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="description" id="description" placeholder="{!! trans('batch.description') !!}" value="">
            @if ($errors->has('description'))
                <span class="help-block">{{ $errors->first('description') }}</span>
            @endif
        </div>
    </div>

    <button type="submit" class="btn btn-success pull-right">{!! trans('messages.button.save') !!}</button>
    @if(Session::has('flash_message'))
        <span class="text-success pull-right animated fadeOut" style="margin: 0.5em;">{!! Session::get('flash_message') !!}</span>
    @endif
</form>

