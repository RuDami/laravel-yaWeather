@extends('adminlte::page')

@section('title', 'Создать отчет')

@section('content_header')
    <h1>Создать отчет</h1>
@stop

@section('content')
    <form class="row" action="" id="report_form">

        <select name="cities[]" id=""
                multiple
                class="selectpicker show-tick col-sm-12"
                data-style="btn-primary"
                data-width="auto"
                data-actions-box="true"
                data-live-search="true"
                title="Выберите нужные города...">

            @foreach($cities as $city)
                <option value="{{$city->id}}">{{$city->name}}</option>
            @endforeach
        </select>
        <div class="col-sm-12">
            @csrf
            <input type="submit" name="create" id="create" class="btn btn-success mt-5 btn-sm" value="Создать">
        </div>
    </form>
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
            $('#report_form').on('submit', function (e) {
                e.preventDefault();
                let method = "POST";
                let action_url = '{{route('admin.reports.store')}}'
                let data = $(this).serialize();

                $.ajax({
                    url: action_url,
                    method: method,
                    data: data,
                    dataType: "json",
                    beforeSend: function () {
                        $('#form_result').html('<p class="alert alert-default-dark mt-3">Создаем отчет...</p>');
                    },
                    success: function (data) {
                        let html = '';
                        if (data.errors) {
                            console.log(data.errors.length);
                            html = '<div class="alert alert-danger mt-3">';
                            for (let i = 0; i < data.errors.length; i++) {
                                html += '<p class="mb-0">' + data.errors[i].text + '</p>';
                            }
                            html += '</div>';
                            $("#form_result").html(html);
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success mt-3">' +
                                data.success.text + '</div>';
                            $("#form_result").html(html);
                            console.log(data.success);
                            window.location.href = data.success.redirectTo;
                        }

                    }
                })
            });
        });

    </script>
@stop
