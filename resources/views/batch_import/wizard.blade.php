@extends('layouts.app')

@section('contentheader_title')
    {!! $title !!}
@endsection

@section('contentheader_description')
    {!! $subtitle !!} - {!! trans('batch_import.' . $active_board ) !!}
@endsection

@section('main-content')
    <div class="box box-prezzogiusto">
        <div class="box-body">
            <div class="board">
                <div class="board-inner" >
                    <ul class="nav nav-tabs" id="myTab">
                        <div class="liner"></div>
                        <li class="{{ ($active_board == 'upload') ? 'active' : '' }} col-xs-2">
                            <a href="#upload" data-toggle="tab" title="{{ trans('batch.upload') }}">
                                <span class="round-tabs {{ ($active_board == 'upload') ? 'active-board' : 'disabled-board' }}"><i class="fa fa-lg fa-cloud-upload"></i></span>
                            </a>
                        </li>

                        <li class="col-xs-2 {{ ($active_board == 'show_content') ? 'active' : 'disabled' }}">
                            <a href="#show_content" class="{{ ($active_board == 'show_content') ? 'active' : 'disabled' }}" data-toggle="tab" title="{{ trans('batch.show_content') }}">
                                <span class="round-tabs {{ ($active_board == 'show_content') ? 'active-board' : 'disabled-board' }}"><i class="fa fa-lg fa-address-book"></i></span>
                            </a>
                        </li>
                        <li class="col-xs-2 {{ ($active_board == 'matching') ? 'active' : 'disabled' }}">
                            <a href="#mapping" class="{{ ($active_board == 'matching') ? 'active' : 'disabled' }}" data-toggle="tab" title="{{ trans('batch.matching') }}">
                                <span class="round-tabs {{ ($active_board == 'matching') ? 'active-board' : 'disabled-board' }}"><i class="fa fa-lg fa-puzzle-piece"></i></span>
                            </a>
                        </li>
                        <li class="col-xs-2 {{ ($active_board == 'mapping') ? 'active' : 'disabled' }}">
                            <a href="#mapping" class="{{ ($active_board == 'mapping') ? 'active' : 'disabled' }}" data-toggle="tab" title="{{ trans('batch.mapping') }}">
                                <span class="round-tabs {{ ($active_board == 'mapping') ? 'active-board' : 'disabled-board' }}"><i class="fa fa-lg fa-map-signs"></i></span>
                            </a>
                        </li>
                        <li class="col-xs-2 {{ ($active_board == 'save') ? 'active' : 'disabled' }}">
                            <a href="#save" class="{{ ($active_board == 'save') ? 'active' : 'disabled' }}" data-toggle="tab" title="{{ trans('batch.save') }}">
                                <span class="round-tabs {{ ($active_board == 'save') ? 'active-board' : 'disabled-board' }}"><i class="fa fa-lg fa-check"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade {{ ($active_board == 'upload') ? 'in active' : '' }}" id="upload" style="padding-top: 10px">
                        @if($active_board == 'upload')
                            @include('batch_import.upload')
                        @endif
                    </div>

                    <div class="tab-pane fade {{ ($active_board == 'show_content') ? 'in active' : '' }}" id="show_content"style="padding-top: 10px">
                        @if($active_board == 'show_content')
                            @include('batch_import.show_content')
                        @endif
                    </div>

                    <div class="tab-pane fade {{ ($active_board == 'matching') ? 'in active' : '' }}" id="matching"style="padding-top: 10px">
                        @if($active_board == 'matching')
                            @include('batch_import.matching')
                        @endif
                    </div>

                    <div class="tab-pane fade {{ ($active_board == 'mapping') ? 'in active' : '' }}" id="mapping"style="padding-top: 10px">
                        @if($active_board == 'mapping')
                            @include('batch_import.mapping')
                        @endif
                    </div>

                    <div class="tab-pane fade {{ ($active_board == 'save') ? 'in active' : '' }}" id="save"style="padding-top: 10px">
                        @if($active_board == 'save')
                            <div class="col-md-8 col-md-offset-2">
                                <div class="callout callout-success">
                                    <h3><i class="fa fa-fw fa-check"></i> Processo di caricamento avviato correttamente!</h3>
                                    <h4>Attendi la notifica di completamento</h4>
                                </div>
                                <a href="{{ action($action . '@imports', $parameters) }}" class="btn btn-primary">{!! trans('batch_import.batch_import_log') !!}</a>
                            </div>
                        @endif
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-scripts')
<script type="text/javascript">
    $(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
        if ($(this).hasClass("disabled")) {
            e.preventDefault();
            return false;
        }
    });
</script>

<script>
    $(document).ready(function(){
        $('#csv_columns span').each(function(){
            column_name = $(this).attr('id').replace('-badge','');
            $(this).html($('#table_columns').find('#' + column_name).length);
        });
    });


    $( "#csv_columns li" ).draggable({
        opacity: 0.6,
        helper: 'clone',
        zIndex: 100,
    });

    $('#table_columns input')
        .not('[name=_token]')
        .on('tokenfield:removedtoken', function (e) {
            $('#' + e.attrs.value + '-badge').html(parseInt($('#' + e.attrs.value + '-badge').html()) - 1 );
        })
        .tokenfield();

    $( "#table_columns input" ).droppable({
        drop: function(event, ui){
            param = $(ui.draggable).data('value');
            element = '#' + this.id.replace('-tokenfield','');
            $(element).tokenfield('setTokens',  $(element).tokenfield('getTokensList', ',') + ',' + param );

            $('#' + param + '-badge').html(parseInt($('#' + param + '-badge').html()) + 1);

        }
    });

    $('#unique_key').select2();
</script>
@endsection