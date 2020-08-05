@extends('adminlte::page')

@section('title', 'Погода')

@section('content_header')
    <h1>Погода</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-body with-table">
                    <table class="table table-bordered datatable" width="100%" id="weather">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID отчета</th>
                            <th>Id города</th>
                            <th>Статус</th>
                            <th>Иконка</th>
                            <th>Описание</th>
                            <th>Температура</th>
                            <th>Мин. темп</th>
                            <th>Макс. темп</th>
                            <th>Влажность</th>
                            <th>Дата</th>
                            <th>Действие</th>
                        </tr>
                        </thead>


                        <tfoot>
                        <tr class="replace-inputs">
                            <th>ID</th>
                            <th>ID отчета</th>
                            <th>Id города</th>
                            <th>Статус</th>
                            <th>Иконка</th>
                            <th>Описание</th>
                            <th>Температура</th>
                            <th>Мин. темп</th>
                            <th>Макс. темп</th>
                            <th>Влажность</th>
                            <th>Дата</th>
                            <th>Действие</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="coonfirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Подтверждение</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <h4>Вы уверены что хотите удалить эти данные?</h4>
                </div>
                <div class="modal-footer">
                    <form action="" id="delete_form" class="form-horisontal">
                        @csrf
                        <div class="form-group row" align="center">
                            <button type="submit" name="ok_button" id="ok_button" class="btn btn-danger mr-2">Удалить
                            </button>
                            <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            let id = null;
            $("#weather").DataTable({
                ajax: "{!! route('admin.weather.index') !!}",
                idSrc: 'id',
                table: "#weather",
                dom: 'Brftip',
                processing: true,
                serverSide: true,
                paging: true,
                ordering: true,
                info: true,
                searching: true,
                order: [[1, 'asc']],

                columns: [
                    {data: "id"},
                    {data: 'report_id'},
                    {data: 'city_id'},
                    {data: 'status'},
                    {data: 'icon'},
                    {data: 'condition'},
                    {data: 'temp'},
                    {data: 'temp_min'},
                    {data: 'temp_max'},
                    {data: 'humidity'},
                    {data: 'date'},
                    {data: "action", name: "action", orderable: false},
                ],

            });
            $(document).on('click', '.delete', function () {
                event.preventDefault();
                id = $(this).attr('id');
                $('#ok_button').text('Удалить');
                $('#coonfirmModal').modal('show');
            });
            $('#delete_form').on('submit', function () {
                event.preventDefault();
                $.ajax({
                    url: '/admin/weather/' + id,
                    method: 'DELETE',
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $('#ok_button').text('Удаляем...');
                    },
                    success: function (data) {
                        $('#coonfirmModal').modal('hide');
                        $("#weather").DataTable().ajax.reload();
                        alert(data.success);
                    }

                })
            })

        });

    </script>
@stop
