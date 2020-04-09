
<!-- Create the tabs -->
<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li class="active"><a href="#control-sidebar-search-tab" data-toggle="tab"><i class="fa fa-search"></i></a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
    <!-- Home tab content -->
    <div class="scrollable tab-pane active" id="control-sidebar-search-tab" style="overflow-x: auto">
        <form class="form" method="get" action="{!! action($action . '@index', $parameters) !!}">
            @foreach($fields as $field)
                @if(!isset($fields_types[$field]) or (isset($fields_types[$field][0]) and !in_array($fields_types[$field][0]->type,['hidden','download'])))
                    <div class="form-group {{ $errors->has($field) ? 'has-error' : '' }}">
                        <label class="control-label">{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}</label>
                        <div class="">
                            @if(isset($fields_types[$field]))
                                @foreach($fields_types[$field] as $type)
                                    @if(in_array($type->type,['hidden']))
                                        <input type="hidden" class="form-control" name="s_{{ $field }}" id="{{ $field }}" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" value="{{ app('request')->input('s_' . $field) }}">
                                    @endif

                                    @if(in_array($type->type,['datetime']))
                                        <input type="text" class="form-control" name="s_{{ $field }}" id="{{ $field }}" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" value="{{ app('request')->input('s_' . $field) }}">
                                    @endif

                                    @if(in_array($type->type,['relation']))
                                        <select class="form-control select-async" data-action="{{ $type->action }}" name="s_{{ $field }}" id="{{ $field }}" >
                                            <option value=""> -- </option>
                                            @if(app('request')->input($field))
                                                <?php
                                                $autocomplete = explode('_', base64_decode(app('request')->input('s_' . $field)));
                                                ?>
                                                <option value="{{ $autocomplete[0] }}" selected>{{ $autocomplete[1] }}</option>
                                            @elseif(isset($data->$field))
                                                <option value="{!! $data->$field !!}" selected>{{ $data->{$type->relation}->{$type->attribute} }}</option>
                                            @endif
                                        </select>
                                    @endif

                                    @if(in_array($type->type,['boolean','select']))
                                        <select class="form-control" name="s_{{ $field }}" id="{{ $field }}">
                                            <option value=""> -- </option>
                                            @foreach($type->values as $i => $value)
                                                <option value="{{ $i }}" {{ (app('request')->input('s_' . $field) == $i) ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                @endforeach
                            @else
                                <input type="text" class="form-control" name="s_{{ $field }}" id="{{ $field }}" placeholder="{!! ($translate_fields) ? trans($resource . '.' . $field) : $field !!}" value="{{ app('request')->input('s_' . $field)}}">
                            @endif

                            @if ($errors->has($field))
                                <span class="help-block">{{ $errors->first($field) }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            <button type="submit" class="btn btn-primary btn-block text-bold">{!! trans('messages.button.search') !!}</button>
            <a href="{!! action($action . '@index', $parameters) !!}" type="submit" class="btn btn-warning btn-block text-bold">{!! trans('messages.button.reset') !!}</a>
        </form>
    </div><!-- /.tab-pane -->
</div>


@section('js-scripts')
    @parent
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
                                    console.log(data.data);

                                    return{
                                        results: data.data,
                                    };
                                },
                            },
                        });
                    @endif
                @endforeach
            @endif
        @endforeach
    </script>
@endsection