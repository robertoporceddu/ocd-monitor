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
            <div class="box box-prezzogiusto">
                <div class="box-header with-border">
                    <div class="btn-group" role="group">
                        <a href="{{ action($action . '@formUpload',$parameters) }}" class="btn btn-primary"> {!! trans('messages.button.new_import') !!}</a>
                    </div>

                </div>
                <div class="box-body">
                    <table class="table table-responsive table-striped table-bordered table-hover table-condensed" style="font-size: medium">
                        <thead>
                            <tr class="bg-prezzogiusto">
                                <th></th>
                                @foreach($fields as $field)
                                <th onclick="window.location = '{{ url(app('request')->fullUrlWithQuery(['o_' . $field => (app('request')->input('o_' . $field)) == 'asc' ? 'desc' : 'asc'])) }}'">
                                    <i class="fa fa-fw {{ empty(app('request')->input('o_' . $field)) ? 'fa-sort' : ((app('request')->input('o_' . $field)) == 'asc' ? 'fa-sort-asc' : 'fa-sort-desc') }}"></i>
                                    {!! trans('batch_import.' . $field) !!}
                                </th>
                                @endforeach

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr data-row-id="{{ $row->id }}">
                                    <td>
                                        <div class="btn-group pull-left">
                                            <button class="btn btn-menu" data-toggle="dropdown" style="padding: 0px 10px">
                                                <i class="fa fa-lg fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu custom-dropdown-menu">
                                                <li><a href="" data-link="{!! action($action . '@delete', array_merge($parameters,['id'=>$row->id])) !!}" data-toggle="modal" data-target="#delete-confirm-modal">{!! trans('messages.button.remove') !!}</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    @foreach($fields as $field)
                                        @if(isset($fields_types[$field]))
                                            @foreach($fields_types[$field] as $type)
                                                <td>{!! ($row->$field) ? fieldType($row, $field, $type) : ''  !!}</td>
                                            @endforeach
                                        @else
                                            <td style="vertical-align: middle">{{ $row->$field }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <div class="col-md-6">
                        <form class="form-inline pull-left" method="get" id="per-page-form" action="{!! action($action . '@imports',$parameters) !!}">
                            <div class="form-group">
                                <label style="margin: 0.5em 0.5em 0 0; font-weight: normal">Risultati per pagina</label>
                                <select name="per_page" class="form-control input-small pull-right" onchange="javascript:$('#per-page-form').submit()" style="width: 5em">
                                    <option value="25" {!! Request::input('per_page') == 25 ? 'selected' : '' !!}>25</option>
                                    <option value="50" {!! Request::input('per_page') == 50 ? 'selected' : '' !!}>50</option>
                                    <option value="100" {!! Request::input('per_page') == 100 ? 'selected' : '' !!}>100</option>
                                    <option value="all" {!! Request::input('per_page') == 'all' ? 'selected' : '' !!}>Tutti</option>
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

@section('js-scripts')
    <script type="text/javascript">
        $('#delete-confirm-modal').on('show.bs.modal', function (event) {
            $(this).find('#btn-delete').attr('href', $(event.relatedTarget).data('link'));
        });
    </script>
@endsection

