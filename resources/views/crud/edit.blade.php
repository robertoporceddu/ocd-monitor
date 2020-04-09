@extends('layouts.app')

@section('contentheader_title')
    {!! $title !!}
@endsection

@section('main-content')
    <form class="form-horizontal" method="post" action="{{ action($action . '@save',(isset($data)) ? array_merge($parameters,['id' => $data->id]) : $parameters) }}" enctype="multipart/form-data">
        <div class="box box-prezzogiusto">
            <div class="box-header with-border">
                <h3 class="box-title">
                    {!! isset($data->id) ?  trans('messages.button.edit') : trans('messages.button.new') !!}
                </h3>
            </div>
            <div class="box-body">
                {{ csrf_field() }}
                @foreach($fields as $field)
                    @if(!isset($fields_types[$field]) or (isset($fields_types[$field][0]) and $fields_types[$field][0]->type != 'hidden'))
                        <div class="form-group {{ $errors->has($field) ? 'has-error' : '' }}">
                            <label class="col-md-2 control-label">{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}</label>
                            <div class="col-md-6">
                        @if(isset($fields_types[$field]))
                            @foreach($fields_types[$field] as $type)
                                @if(in_array($type->type,['hidden']))
                                    <input type="hidden" class="form-control" name="{{ $field }}" id="{{ $field }}" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" value="{{ (isset($data->$field)) ? $data->$field : old($field) }}">
                                @endif

                                @if(in_array($type->type,['file','img','profile_image']))
                                    <input type="file" class="form-control" name="{{ $field }}" id="{{ $field }}" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" value="{{ (isset($data->$field)) ? $data->$field : old($field) }}">
                                @endif

                                @if(in_array($type->type,['password']))
                                    <input type="password" class="form-control" name="{{ $field }}" id="{{ $field }}" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" >
                                    @if($type->confirmed)
                                        <input type="password" class="form-control" name="{{ $field }}_confirmation" id="{{ $field }}_confirmation" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" style="margin-top: 5px">
                                    @endif
                                @endif

                                @if(in_array($type->type,['relation']))
                                    <select class="form-control select-async" data-action="{{ $type->action }}" name="{{ $field . (($type->relation_type == 'belongToMany') ? '[]' : '') }}" id="{{ $field }}" {{ ($type->relation_type == 'belongToMany') ? 'multiple' : '' }} >
                                        <option value=""> -- </option>
                                        @if(app('request')->input($field))
                                            <?php
                                                $autocomplete = explode('_', base64_decode(app('request')->input($field)));
                                            ?>
                                            <option value="{{ $autocomplete[0] }}" selected>{{ $autocomplete[1] }}</option>
                                        @elseif(isset($data->$field))
                                            @if($type->relation_type == 'belongToMany')
                                                @foreach($data->{$type->relation} as $item)
                                                    <option value="{!! $item->id !!}" selected>{{ $item->{$type->attribute} }}</option>
                                                @endforeach
                                            @else
                                                <option value="{!! $data->$field !!}" selected>{{ $data->{$type->relation}->{$type->attribute} }}</option>
                                            @endif
                                        @endif
                                    </select>
                                @endif

                                @if(in_array($type->type,['boolean','select']))
                                    <select class="form-control" name="{{ $field }}" id="{{ $field }}">
                                        <option value=""> -- </option>
                                        @foreach($type->values as $i => $value)
                                            <option value="{{ $value }}" {{ (isset($data->$field) and $data->$field == $value) ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endforeach
                        @else
                            <input type="text" class="form-control" name="{{ $field }}" id="{{ $field }}" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" value="{{ (isset($data->$field)) ? $data->$field : old($field) }}">
                        @endif

                        @if ($errors->has($field))
                            <span class="help-block">{{ $errors->first($field) }}</span>
                        @endif
                    </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right text-bold">{!! trans('messages.button.save') !!}</button>
            </div>
        </div>
    </form>
@stop

@section('js-scripts')
    <script type="text/javascript">
        @foreach($fields as $field)
             @if(isset($fields_types[$field]))
                @foreach($fields_types[$field] as $type)
                    @if(in_array($fields_types[$field][0]->type,['relation']))
                        $("#{{ $field }}").select2({

                            ajax: {
                                url: $("#{{ $field }}").data('action'),
                                dataType: 'json',
                                delay: 250,

                                data: function (params) {
                                    return {
                                        's_name': params.term,
                                    };
                                },
                                processResults: function (data, params) {
                                    return{
                                        results: data.data,
                                    };
                                },
                            },
//                            tags: true,
                            tokenSeparators: [','],
                        });
                    @endif
                @endforeach
             @endif
        @endforeach
    </script>
@endsection