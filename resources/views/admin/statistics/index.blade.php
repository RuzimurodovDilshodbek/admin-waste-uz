@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            Statistikalar {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Statistic">
                    <thead>
                    <tr>
                        <th width="10"></th>
                        <th>ID</th>
                        <th>Nomi</th>
                        <th>Nomi Ru</th>
                        <th>Nomi En</th>
                        <th>Soni</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($statistics as $key => $statistic)
                        <tr data-entry-id="{{ $statistic->id }}">
                            <td></td>
                            <td>{{ $statistic->id ?? '' }}</td>
                            <td>{{ $statistic->name_uz ?? '' }}</td>
                            <td>{{ $statistic->name_ru ?? '' }}</td>
                            <td>{{ $statistic->name_en ?? '' }}</td>
                            <td>{{ $statistic->count ?? '' }}</td>
                            <td>
                                @can('statistic_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.statistics.edit', $statistic->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('statistic_delete')
                                    <form action="{{ route('admin.statistics.destroy', $statistic->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        @method('DELETE')
                                        @csrf
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            @can('statistic_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.statistics.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: { ids: ids, _method: 'DELETE' }})
                            .done(function () { location.reload() })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[ 1, 'desc' ]],
                pageLength: 10,
            });
            let table = $('.datatable-Statistic:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })
    </script>
@endsection
