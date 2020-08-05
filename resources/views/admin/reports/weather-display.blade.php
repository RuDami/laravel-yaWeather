<div class="row">
    <div class="col-12 mb-4">
        <img src="https://yastatic.net/weather/i/icons/blueye/color/svg/{{$this_weather->icon}}.svg"
             class="img-thumbnail" width="100">
    </div>
    <div class="col-12" style="max-width: 200px;word-break: break-all;">
        <p>ID Погоды: {{$this_weather->id}} </p>
        <p>ID Отчета: {{$this_weather->report_id}}</p>
        <p>ID Города: {{$this_weather->city_id}}</p>
        <p>Статус: {{$this_weather->status}}</p>
        <p>Дата: {{date('d.m.Y', strtotime($this_weather->date)) }}</p>
        <p>Состояние: {{$this_weather->condition}}</p>
        <p>Температура:{{$this_weather->temp}}°</p>
        <p>Влажность: {{$this_weather->humidity}}%</p>

    </div>
</div>
