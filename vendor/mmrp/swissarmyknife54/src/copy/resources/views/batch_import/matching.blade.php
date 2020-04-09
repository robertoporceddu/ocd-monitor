<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 22/02/17
 * Time: 14:39
 */
?>

<div class="row">
    <div id="" class="col-md-12">
        <h3>{{ trans('batch.columns_matching') }}</h3>
        <form id="" class="form" method="post" action="{{ action($action . '@createColumn', ['file_id' => $file_id]) }}" enctype="multipart/form-data">
            <div class="form-group pull-right">
                @if(array_intersect($csv_columns,$table_columns))
                    <button class="btn btn-primary " type="submit">{!! trans('messages.button.save') !!}</button>
                @endif
                <a href="{{ action($action . '@mapping',['file_id' => $file_id]) }}" class="margin-r-5 btn btn-warning">{!! trans('messages.button.skip') !!}</a>
            </div>
            {!! csrf_field() !!}
            @foreach($csv_columns as $column)
                @if(!isset($table_columns[$column]))
                <div class="form-group">
                    <div class="" style="margin: 0">
                        <label class="">
                            <input type="checkbox" name="columns[]" value="{{ $column }}" class="">
                            {{ $column }}
                        </label>
                    </div>
                </div>
                @endif
            @endforeach

            @if(array_intersect($csv_columns,$table_columns))
            <div class="form-group pull-right">
                @if(array_intersect($csv_columns,$table_columns))
                    <button class="btn btn-primary " type="submit">{!! trans('messages.button.save') !!}</button>
                @endif
                <a href="{{ action($action . '@mapping',['file_id' => $file_id]) }}" class="margin-r-5 btn btn-warning">{!! trans('messages.button.skip') !!}</a>
            </div>
            @endif
        </form>
    </div>
</div>


