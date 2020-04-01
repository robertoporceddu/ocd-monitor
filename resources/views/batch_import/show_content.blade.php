<a class="btn btn-primary pull-right" href="{{ action($action . '@matching',array_merge($parameters,['file_id' => $file_id])) }}">{!! trans('batch_import.mapping') !!} <i class="fa fa-fw fa-arrow-right"></i></a>

<table class="table table-responsive table-striped table-bordered table-hover table-condensed" >
    <thead>
        <tr>
            <th class="bg-yellow-active" style="font-weight: bold;">COLONNA</th>
            <th class="bg-yellow" colspan="3" style="text-align: center;font-weight: bold">VALORI</th>
        </tr>
    </thead>
    <tbody>
        @foreach($columns as $column)
        <tr>
            <td class="bg-gray-active" style="font-weight: bold">{{ $column }}</td>
            @foreach($rows as $row)
                <td>{{ $row[$column] }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<a class="btn btn-primary pull-right" href="{{ action($action . '@matching',array_merge($parameters,['file_id' => $file_id])) }}">{!! trans('batch_import.mapping') !!} <i class="fa fa-fw fa-arrow-right"></i></a>
