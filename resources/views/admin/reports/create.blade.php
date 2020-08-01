@extends('adminlte::page')

@section('title', 'Создать отчет')

@section('content_header')
    <h1>Создать отчет</h1>
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
                    <div id="form_result">

                    </div>
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
            $("#create").click(function () {
                event.preventDefault();
                let method = "POST";
                let action_url = '{{route('admin.reports.store')}}'
                let data = {_token: "{{ csrf_token() }}"};
                data = JSON.stringify(data);
                $.ajax({
                    url: action_url,
                    method: method,
                    data: data,
                    contentType: 'application/json; charset=utf-8',
                    processData: false,
                    beforeSend: function () {
                        $('#form_result').html('<p>Создаем отчет...</p>');
                    },
                    success: function (data) {
                        let html = '';
                        if (data.errors) {
                            html = '<div class="alert alert-danger">';
                            for (let i = 0; i < data.errors.length; i++) {
                                html += '<p class="mb-0">' + data.errors[i] + '</p>';
                            }
                            html += '</div>';
                            $("#form_result").html(html);
                        }
                        if (data.success) {
                            html = '<div class="alert allert-success">' +
                                data.success.text + '</div>';
                            $("#form_result").html(html);
                            console.log(data.success.text);
                            window.location.href = data.success.redirectTo;
                        }

                    }
                })
            });
        });

    </script>
@stop
