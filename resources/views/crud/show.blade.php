@extends('layouts.app')

@section('contentheader_title')
    {!! $title !!}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-5">
            <div class="box box-prezzogiusto">
                <div class="box-body box-profile">

                    <h3 class="profile-username text-center">{!! $data->name !!}</h3>

                    @if($available_action['edit'])
                        <a href="{!! action($action . '@edit',array_merge($parameters,['id'=>$data->id])) !!}" class="btn btn-block btn-primary text-bold">{!! trans('messages.button.edit') !!}</a>
                    @endif

                    @if($available_action['delete'])
                        <a href="" data-link="{!! action($action . '@delete',array_merge($parameters,['id'=>$data->id])) !!}" class="btn btn-block btn-danger btn-no-loading text-bold" data-toggle="modal" data-target="#delete-confirm-modal">{!! trans('messages.button.remove') !!}</a>
                    @endif
                    <hr />

                    @if(count($custom_actions))
                        @foreach($custom_actions as $custom_action)
                            <a href="{!! $custom_action['link'] !!}" class="btn btn-block btn-info text-bold">{!! $custom_action['title'] !!}</a>
                        @endforeach
                        <hr />
                    @endif

                    @foreach($fields as $field)
                        @if($field != 'name')
                            <div>
                                @if(!isset($fields_types[$field]) or (isset($fields_types[$field][0]) and $fields_types[$field][0]->type != 'hidden'))
                                    <strong><i class="fa fa-circle margin-r-5"></i> {!! ($translated_fields) ? trans($resource . '.' . $field) : $field !!}</strong>
                                    <span class="text-muted">
                                        @if(isset($fields_types[$field]))
                                            @foreach($fields_types[$field] as $type)
                                                {!! ($data->$field) ? fieldType($data, $field, $type) : '' !!}
                                            @endforeach
                                        @else
                                            {!! $data->$field !!}
                                        @endif

                                    </span>
                                @endif
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
        <div class="col-md-7">
            @if(View::exists('resources.' . $resource.'.show'))
                @include('resources.' . $resource.'.show')
            @endif
        </div>
    </div>
@stop

@section('js-scripts')
    <script type="text/javascript">
        $('#delete-confirm-modal').on('show.bs.modal', function (event) {
            $(this).find('#btn-delete').attr('href', $(event.relatedTarget).data('link'));
        });
    </script>
@stop