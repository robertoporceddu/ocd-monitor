<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class="active"><a href="#permissions" data-toggle="tab">{{ trans('permission.permissions') }}</a></li>
        <li class="pull-left header"><i class="fa fa-cogs"></i> Impostazioni</li>
    </ul>
    <div class="tab-content no-padding">
        <div class="tab-pane active" id="permissions" style="overflow-x: auto; ">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('permission.permission') }}</th>
                        @foreach($additional_data['actions_list'] as $key => $value)
                            <th>{{ $key }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @foreach($additional_data['permissions_matrix'] as $permissions => $actions)
                    <tr>
                        <td>{{ $permissions }}</td>
                        @foreach($additional_data['actions_list'] as $key => $value)
                            <td class="text-center">
                                @if(isset($actions[$key]))
                                    <button id="permission-{{  $actions[$key]['id'] }}" data-status="{{ ($actions[$key]['enabled']) ? 'detach' : 'attach' }}" onclick="javascript:tooglePermission({{ $actions[$key]['id'] }})" class="btn btn-xs {{ ($actions[$key]['enabled']) ? 'btn-default' : 'btn-default' }}">
                                        <i class="fa fa-fw {{ ($actions[$key]['enabled']) ? 'fa-check text-green' : 'fa-times text-red' }}"></i>
                                    </button>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.tab-content -->
</div>

@section('js-scripts')
<script type="text/javascript">
    function tooglePermission(id){
        action = $('#permission-' + id).data('status');

        if(action == 'detach'){
            icon = 'fa-times text-red';
            url = '{{ action('Management\User\UserController@detachPermission',['id' => $data->id,'permission_id' => '']) }}/' + id;
        } else {
            icon = 'fa-check text-green';
            url = '{{ action('Management\User\UserController@attachPermission',['id' => $data->id,'permission_id' => '']) }}/' + id;
        }

        var ajax_opt = {
            url: url,
            async: true,
            type: 'GET',
            cache: false,
            dataType: 'json',
        };

        $.ajax(
            ajax_opt
        ).success(function(data) {
            if(data.code == 200){
                $('#permission-' + id).html('<i class="fa fa-fw ' + icon + '"></i>');
                $('#permission-' + id).data('status',(action == 'attach' ? 'detach' : 'attach'));
            } else {
                errorResponse(data);
            }
        });
    }

    function errorResponse(data) {
        if(data.code == '403'){
            alert(data.message);
        }
    }
</script>
@endsection