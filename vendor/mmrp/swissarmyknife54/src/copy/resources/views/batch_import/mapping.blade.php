<div class="row">
    <div id="" class="col-md-8">
        <form id="table_columns" class="form" method="post" action="{{ action($action . '@batch',array_merge($parameters,['file_id' => $file_id])) }}" enctype="multipart/form-data">
            <h3>Criterio di univocità</h3>
            <div class="row">
                <div class="col-sm-12">
                    <select class="form-control" id="unique_key" name="unique_key[]" multiple="multiple">
                        @foreach($table_columns as $table_column)
                            <option value="{{ $table_column }}">{{ $table_column }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <hr />

            <h3>{{ trans('batch.columns_target') }}
                <div class="btn-group pull-right">
                    <button class="btn btn-primary" type="submit">{!! trans('messages.button.save') !!}</button>
                </div>
            </h3>

            {!! csrf_field() !!}
            @foreach($table_columns as $table_column)
                <div class="form-group">
                <label class="control-label">{!! $table_column !!}</label>
                <div class="">
                    <input type="text" class="form-control input-sm" name="{!! $table_column !!}" id="{!! $table_column !!}" value="{{ (in_array($table_column,$csv_columns) ? $table_column : '') }}">
                </div>
            </div>
            @endforeach

            <div class="btn-group pull-right">
                <button class="btn btn-primary" type="submit">{!! trans('messages.button.save') !!}</button>
            </div>
        </form>
    </div>
    <div id="csv_columns" class="col-md-4">
        <h3>{{ trans('batch.columns_file') }}</h3>
        <ul class="list-group">
        @foreach($csv_columns as $csv_column)
            <li class="list-group-item" data-value="{{ $csv_column }}"><span id="{{ $csv_column }}-badge"class="badge"></span>{!! $csv_column !!}</li>
        @endforeach
        </ul>
    </div>
</div>