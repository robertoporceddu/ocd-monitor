<!-- Confirm Delete Modal -->
<div class="modal modal-danger fade" id="delete-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h1><?php echo Lang::get('messages.are_you_sure') ?></h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline btn-no-loading text-bold" data-dismiss="modal">{!! trans('messages.button.reset') !!}</button>
                <a href="#" id="btn-delete" class="btn btn-sm btn-outline text-bold"
                   onclick="javascript:$(this).attr('disabled','disabled').html('<i class=\'fa fa-fw fa-spinner fa-pulse\'></i> ' + $(this).html())">
                    {!! trans('messages.button.remove') !!}
                </a>
            </div>
        </div>
    </div>
</div>

@section('js-scripts')
    <script type="text/javascript">
        $('#delete-confirm-modal').modal({ "show": false });
    </script>
    @parent
@stop