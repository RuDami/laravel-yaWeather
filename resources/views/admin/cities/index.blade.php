@extends('adminlte::page')

@section('title', 'Города')

@section('content_header')
    <h1>Города</h1>
@stop

@section('content')
    <div class="row">

        <div class="col-sm-12">
            <button type="button" name="create" id="create" class="btn btn-success btn-sm">Создать</button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-body with-table">
                    <table class="table table-bordered datatable" width="100%" id="cities">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Широта</th>
                            <th>Долгота</th>
                            <th>Действие</th>
                        </tr>
                        </thead>


                        <tfoot>
                        <tr class="replace-inputs">
                            <th>ID</th>
                            <th>Название</th>
                            <th>Широта</th>
                            <th>Долгота</th>
                            <th>Действие</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="formModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="city_form" class="form-horisontal">
                    <div class="modal-body"><span id="form_result"></span>
                        @csrf
                        <div class="form-group row">
                            <label for="city_name" class="control-label col-md-4">
                                Название города:
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="city_name" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city_lat" class="control-label col-md-4">
                                Широта:
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="lat" name="lat" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city_lon" class="control-label col-md-4">
                                Долгота:
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="lon" name="lon" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group row" align="center">
                            <input type="hidden" id="action" name="action" class="form-control">
                            <input type="hidden" name="hidden_id" id="hidden_id">
                            <input type="submit" name="action_button" id="action_button"
                                   class="btn btn-primary btn-block"
                                   value="Добавить">
                        </div>
                    </div>
                </form>

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
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            let id = null;
            $("#cities").DataTable({
                ajax: "{!! route('admin.cities.index') !!}",
                idSrc: 'id',
                table: "#cities",
                //dom: 'Pfrtip',
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
                    {data: "id"},
                    {data: "name", name: "name"},
                    {data: "lon", name: "lon"},
                    {data: "lat", name: "lat"},
                    {data: "action", name: "action", orderable: false},
                ],

            });
            $("#create").click(function () {
                $("#formModal .modal-title").text("Добавить запись");
                $("#formModal #action_button").val("Добавить");
                $("#action").val('Add');
                $('#city_form')[0].reset();
                $("#form_result").html('');
                $('#formModal').modal('show');
            });
            $('#city_form').on('submit', function () {
                event.preventDefault();
                let action_url = '';
                let method = '';
                if ($('#action').val() == 'Add') {
                    method = "POST";
                    action_url = '{{route('admin.cities.store')}}'
                }
                if ($('#action').val() == 'Edit') {
                    let id = $('#hidden_id').val();
                    method = "PUT";
                    action_url = "/admin/cities/" + id;
                }
                $.ajax({
                    url: action_url,
                    method: method,
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (data) {
                        let html = '';
                        if (data.errors) {
                            html = '<div class="alert alert-danger">';
                            for (let i = 0; i < data.errors.length; i++) {
                                html += '<p class="mb-0">' + data.errors[i] + '</p>';
                            }
                            html += '</div>';

                        }
                        if (data.success) {
                            html = '<div class="alert allert-success">' +
                                data.success + '</div>';
                            $('#city_form')[0].reset();
                            $('#formModal').modal('hide');
                            $("#cities").DataTable().ajax.reload();
                            alert(data.success);
                        }
                        $("#form_result").html(html);
                    }
                })
            });
            $(document).on('click', '.edit', function () {
                event.preventDefault();
                id = $(this).attr('id');
                $("#form_result").html('');
                $.ajax({
                    url: "/admin/cities/" + id + "/edit",
                    dataType: "json",
                    success: function (data) {
                        $('#city_name').val(data.result.name);
                        $('#lat').val(data.result.lat);
                        $('#lon').val(data.result.lon);
                        $('#hidden_id').val(id);
                        $("#formModal .modal-title").text("Изменить запись");
                        $("#formModal #action_button").val("Изменить");
                        $("#action").val('Edit');
                        $('#formModal').modal('show');
                    }
                })

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
                    url: '/admin/cities/' + id,
                    method: 'DELETE',
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $('#ok_button').text('Удаляем...');
                    },
                    success: function (data) {
                        $('#coonfirmModal').modal('hide');
                        $("#cities").DataTable().ajax.reload();
                        alert(data.success);
                    }

                })
            })

        });

    </script>
@stop
