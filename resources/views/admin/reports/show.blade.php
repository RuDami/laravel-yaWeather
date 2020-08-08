@extends('adminlte::page')

@section('title', 'Просмотреть отчет')

@section('content_header')
    <h1>Просмотр "{{$report->name}}"</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <input action="action"
                   onclick="window.history.go(-1); return false;"
                   type="submit"
                   value="Назад"
                   class="btn btn-default btn-sm">
            <a href="{{route('admin.report.download', $report)}}" target="_blank" name="download" id="download"
               class="btn btn-success btn-sm">Скачать отчет</a>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-body with-table">
                    <table class="table table-bordered datatable" width="100%" id="reports">
                        <thead>
                        <tr>
                            <th>Город</th>
                            <th>Сейчас</th>
                            <th>Завтра</th>
                            <th>Через 1 день</th>
                            <th>Через 2 дня</th>
                            <th>Через 3 дня</th>
                            <th>Через 4 дня</th>
                        </tr>
                        </thead>


                        <tfoot>
                        <tr class="replace-inputs">
                            <th>Город</th>
                            <th>Сейчас</th>
                            <th>Завтра</th>
                            <th>Через 1 день</th>
                            <th>Через 2 дня</th>
                            <th>Через 3 дня</th>
                            <th>Через 4 дня</th>

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

@stop

@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            let id = null;
            $("#reports").DataTable({
                ajax: "/admin/reports/{{$report->id}}",
                idSrc: 'id',
                table: "#reports",
                dom: 'Brftip',
                processing: true,
                serverSide: true,
                paging: true,
                ordering: true,
                info: true,
                searching: true,
                order: [[1, 'asc']],
                searchPanes: {
                    dtOpts: {
                        dom: "tp",
                        paging: true,
                        pagingType: 'numbers',
                        searching: false,
                    }
                },
                columnDefs: [
                    {
                        searchPanes: {
                            dtOpts: {
                                dom: "ti",
                                info: true,
                            }
                        },
                        targets: [2]
                    }
                ],
                columns: [
                    {data: "name"},
                    {data: "weather_0", "searchable": false},
                    {data: "weather_1", "searchable": false},
                    {data: "weather_2", "searchable": false},
                    {data: "weather_3", "searchable": false},
                    {data: "weather_4", "searchable": false},
                    {data: "weather_5", "searchable": false},
                ],

            });
        });

    </script>
@stop
