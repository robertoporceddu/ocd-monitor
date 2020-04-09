<div class="modal modal-default fade" id="multiple-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" action="{{ action($action . '@save', ['id' => 'multiple']) }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">{!! trans('messages.button.multiple-edit') !!}</h4>
                </div>
                <div class="modal-body">
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
                                                <select class="form-control select-async" data-action="{{ $type->action }}" name="{{ $field }}" id="{{ $field }}" >
                                                    <option value=""> -- </option>
                                                    @if(app('request')->input($field))
                                                        <?php
                                                        $autocomplete = explode('_', base64_decode(app('request')->input($field)));
                                                        ?>
                                                        <option value="{{ $autocomplete[0] }}" selected>{{ $autocomplete[1] }}</option>
                                                    @elseif(isset($data->$field))
                                                        <option value="{!! $data->$field !!}" selected>{{ $data->{$type->relation}->{$type->attribute} }}</option>
                                                    @endif
                                                </select>
                                            @endif

                                            @if(in_array($type->type,['boolean','select']))
                                                <select class="form-control" name="{{ $field }}" id="{{ $field }}">
                                                    <option value=""> -- </option>
                                                    @foreach($type->values as $i => $value)
                                                        <option value="{{ $i }}" {{ (isset($data->$field) and $data->$field == $i) ? 'selected' : '' }}>{{ $value }}</option>
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
                <div class="modal-footer">
                    <button type="reset" class="btn btn-warning  text-bold" data-dismiss="modal">{!! trans('messages.button.reset') !!}</button>
                    <button type="submit" class="btn btn-success text-bold">{!! trans('messages.button.save') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
