@extends('layouts.app')

@section('contentheader_title')
    {!! $title !!}
@endsection

@section('contentheader_description')
    {!! $subtitle or '' !!}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <button class="btn btn-info text-bold"  onclick="toggleRightSidebar()">{!! trans('messages.button.search') !!}</button>
                            </div>

                            @if(isset($custom_actions))
                                <div class="btn-group" role="group">
                                    @foreach($custom_actions as $custom_action)
                                        <a href="{{ $custom_action['link'] }}" class="btn btn-primary text-bold"> {!! $custom_action['title'] !!}</a>
                                    @endforeach
                                </div>
                            @endif

                            @if(!isset($trash))
                                <div class="btn-group" role="group">
                                    @if($available_action['insert'])
                                        <a href="{{ action($action . '@insert',$parameters) }}" class="btn btn-primary text-bold"> {!! trans('messages.button.new') !!}</a>
                                    @endif

                                    @if($available_action['edit'] and $available_action['multiple_operations'])
                                        <a data-action="{{ action($action . '@edit', array_merge($parameters,['id' => 'multiple'])) }}"
                                           data-method="GET" class="btn btn-primary text-bold"
                                           onclick="javascript:$('#multiple-action').attr({'action':$(this).data('action'),'method':$(this).data('method')}).submit()" > {!! trans('messages.button.edit') !!}
                                        </a>

                                        <button class="btn btn-primary text-bold" data-toggle="modal" data-target="#multiple-edit">{!! trans('messages.button.multiple-edit') !!}</button>
                                    @endif

                                    @if($available_action['delete'] and $available_action['multiple_operations'])
                                        <a data-action="{{ action($action . '@delete', array_merge($parameters,['id' => 'multiple'])) }}"
                                           data-method="GET" class="btn btn-danger text-bold"
                                           onclick="javascript:$('#multiple-action').attr({'action':$(this).data('action'),'method':$(this).data('method')}).submit()" > {!! trans('messages.button.remove') !!}
                                        </a>
                                    @endif
                                </div>

                                @if($available_action['trash'])
                                    <div class="btn-group" role="group">
                                        <a href="{{ action($action . '@trash', $parameters) }}" class="btn btn-primary text-bold"> {!! trans('messages.button.trash') !!}</a>
                                    </div>
                                @endif
                                <div class="btn-group" role="group">
                                    @if($batch_import)
                                        <a href="{{ action($action . '@imports', $parameters) }}" class="btn btn-primary text-bold"> {!! trans('messages.button.show_import') !!}</a>
                                    @endif
                                </div>
                            @else
                                <div class="btn-group" role="group">
                                    @if($available_action['restore'] and $available_action['multiple_operations'])
                                        <a data-action="{{ action($action . '@restore', array_merge($parameters,['id' => 'multiple'])) }}"
                                           data-method="GET" class="btn btn-primary text-bold"
                                           onclick="javascript:$('#multiple-action').attr({'action':$(this).data('action'),'method':$(this).data('method')}).submit()" > {!! trans('messages.button.refresh') !!}
                                        </a>
                                    @endif
                                    @if($available_action['destroy'] and $available_action['multiple_operations'])
                                        <a data-action="{{ action($action . '@destroy', array_merge($parameters,['id' => 'multiple'])) }}"
                                           data-method="GET" class="btn btn-danger text-bold"
                                           onclick="javascript:$('#multiple-action').attr({'action':$(this).data('action'),'method':$(this).data('method')}).submit()" > {!! trans('messages.button.destroy') !!}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body" >
                    <div style="overflow-x: auto; min-height: 300px">
                        <table class="table table-responsive table-striped table-bordered table-hover table-condensed" style="font-size: medium;">
                            <thead>
                            <tr class="bg-primary">
                                <th style="width: 60px">
                                    @if($available_action['multiple_operations'])
                                        <div class="checkbox squaredThree pull-left" style="margin: 0">
                                            <label class="">
                                                <input type="checkbox" onclick="javascript: $('input:checkbox').not(this).prop('checked', this.checked)">
                                            </label>
                                        </div>
                                </th>
                                @endif
                                @foreach($fields as $field)
                                    @if(!isset($fields_types[$field]) or (isset($fields_types[$field][0]) and $fields_types[$field][0]->type != 'hidden'))
                                        <th style="min-width: 200px" onclick="window.location = '{{ url(app('request')->fullUrlWithQuery(['o_' . $field => (app('request')->input('o_' . $field)) == 'asc' ? 'desc' : 'asc'])) }}'">
                                            <i class="fa fa-fw {{ empty(app('request')->input('o_' . $field)) ? 'fa-sort' : ((app('request')->input('o_' . $field)) == 'asc' ? 'fa-sort-asc' : 'fa-sort-desc') }}"></i>
                                            {!! ($translate_fields) ? trans($resource . '.' . $field) : $field!!}
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <form id="multiple-action" action="" method="">
                                {{ csrf_field() }}
                                @foreach($data as $row)
                                    <tr data-row-id="{{ $row->id }}">
                                        <td style="vertical-align: middle;">
                                            <div style="width: 60px">
                                                @if($available_action['multiple_operations'])
                                                    <div class="checkbox squaredThree pull-left" style="margin: 0">
                                                        <label class="">
                                                            <input type="checkbox" name="rows_id[]" id="rows_id" value="{{ $row->id }}" class="">
                                                        </label>
                                                    </div>
                                            @endif
                                            <!-- Single button -->
                                                <div class="btn-group pull-left">
                                                    <button class="btn btn-menu" data-toggle="dropdown" style="padding: 0px 10px">
                                                        <i class="fa fa-lg fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu custom-dropdown-menu">
                                                        @if(!isset($trash))
                                                            @if($available_action['get'])
                                                                <li><a href="{!! action($action . '@get', array_merge($parameters, ['id' => $row->id])) !!}">{!! trans('messages.button.open') !!}</a></li>
                                                            @endif
                                                            @if($available_action['edit'])
                                                                <li><a href="{!! action($action . '@edit', array_merge($parameters, ['id' => $row->id])) !!}">{!! trans('messages.button.edit') !!}</a></li>
                                                            @endif
                                                            @if($available_action['delete'])
                                                                <li><a href="" data-link="{!! action($action . '@delete', array_merge($parameters, ['id' => $row->id])) !!}" data-toggle="modal" data-target="#delete-confirm-modal">{!! trans('messages.button.remove') !!}</a></li>
                                                            @endif
                                                        @else
                                                            @if($available_action['restore'])
                                                                <li><a href="{{ action($action . '@restore', array_merge($parameters, ['id' => $row->id])) }}">{!! trans('messages.button.refresh') !!}</a></li>
                                                            @endif
                                                            @if($available_action['destroy'])
                                                                <li><a href="" data-link="{!! action($action . '@destroy', array_merge($parameters, ['id' => $row->id])) !!}" data-toggle="modal" data-target="#delete-confirm-modal">{!! trans('messages.button.destroy') !!}</a></li>
                                                            @endif
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($fields as $field)
                                            @if(!isset($fields_types[$field]) or (isset($fields_types[$field][0]) and $fields_types[$field][0]->type != 'hidden'))
                                                @if(isset($fields_types[$field]))
                                                    <td style="vertical-align: middle;">
                                                        @foreach($fields_types[$field] as $type)
                                                            @if($type->type == 'download')
                                                                <a href="{{ action($action . '@download',['path' => base64_encode($row->$field)]) }}">{!! trans('messages.button.download') !!}</a>
                                                            @else
                                                                {!! ($row->$field) ? fieldType($row, $field, $type) : ''  !!}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                @else
                                                    <td style="vertical-align: middle">{!! $row->$field !!}</td>
                                                @endif
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer">

                    <div class="col-md-6">
                        <form id="pagination-form" class="form-inline pull-left" method="get" id="per-page-form">
                            <div class="form-group">
                                <label style="margin: 0.5em 0.5em 0 0; font-weight: normal">Risultati per pagina</label>
                                <select id="perPage" name="per_page" class="form-control input-small pull-right" style="width: 5em" onchange="">
                                    <option value="25" {!! app('request')->input('per_page') == 25 ? 'selected' : '' !!}>25</option>
                                    <option value="50" {!! app('request')->input('per_page') == 50 ? 'selected' : '' !!}>50</option>
                                    <option value="100" {!! app('request')->input('per_page') == 100 ? 'selected' : '' !!}>100</option>
                                    <option value="all" {!! app('request')->input('per_page') == 'all' ? 'selected' : '' !!}>Tutti</option>
                                </select>
                            </div>
                        </form>
                        @if(app('request')->input('per_page') != 'all')
                            <span style="margin: 0.5em 0 0 0.5em; display: inline-block">{{ $data->total() }} Record - Pagina {{ $data->currentPage() }} di {{ $data->lastPage() }} totali.</span>
                        @endif
                    </div>
                    @if(app('request')->input('per_page') != 'all')
                        <div class="pull-right">{!! $data->render() !!}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@include('crud.modal.edit')

@section('control-sidebar')
    @include('crud.search')
@endsection

@section('js-scripts')
    <script type="text/javascript">
        $('#delete-confirm-modal').on('show.bs.modal', function (event) {
            $(this).find('#btn-delete').attr('href', $(event.relatedTarget).data('link'));
        });

        $('#multiple-edit').on('show.bs.modal', function (event) {
            $('table #rows_id').each(function(){
                if($(this).is(':checked')){
                    $('#multiple-edit form').append('<input type="hidden" name="rows_id[]" value="' + $(this).val() +'">');
                }
            });
        });

        if(checkIfExistUrlParameter('s_.+')){
            toggleRightSidebar();
        }

        function toggleRightSidebar()
        {
            if($('body').hasClass('control-sidebar-open')){
                $('#right-sidebar').removeClass('control-sidebar-open');
                $('body').removeClass('control-sidebar-open');
            } else {
                $('#right-sidebar').addClass('control-sidebar-open');
                $('body').addClass('control-sidebar-open');
            }
        }

        $("#perPage").bind('change', function(){


            switch($(this).val()){
                case "25":
                    window.location.href = '{!! url(app('request')->fullUrlWithQuery(['per_page' => 25])) !!}';
                    break;
                case "50":
                    window.location.href = '{!! url((app('request')->fullUrlWithQuery(['per_page' => 50]))) !!}';
                    break;
                case "100":
                    window.location.href = '{!! url(app('request')->fullUrlWithQuery(['per_page' => 100])) !!}';
                    break;
                case "all":
                    window.location.href = '{!! url(app('request')->fullUrlWithQuery(['per_page' => 'all'])) !!}';
                    break;
            }
        });
    </script>
@stop