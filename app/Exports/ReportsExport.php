<?php

namespace App\Exports;

use App\Report;
use App\Weather;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Excel;

class ReportsExport implements FromArray, Responsable
{
    use Exportable;

    protected $report;
    /**
     * Optional Writer Type
     *
     */
    private $writerType = Excel::CSV;


    /**
     * Optional headers
     *
     */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    /**
     * @param Report $report
     */

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function array(): array
    {
        $cities = $this->report->cities()->get(); //Получаем все города которые относятся к данному отчету.

        $head_row = ["Город", "ID Погоды", "ID Отчета", "ID Города", "Статус", "Название иконки", "Состояние",
            "Температура (в °)", "Влажность (в %)", "Дата", "Время создания "];
        $rows[] = &$head_row; // Добавляем верхнюю строку
        foreach ($cities as $city) { // Цикл по городам
            $rows[] = ["---------"]; // Просто разделитель, для удобства чтения
            $weather = Weather::where('report_id', $this->report->id)->where('city_id', $city->id)->get();// Получаем всю погоду где есть указаный отчет и город
            for ($i = 0; $i < count($weather); $i++) { // Заупскаем цикл по погоде
                $this_weather = $weather[$i]->toArray(); // Преобразуем из объекта в массив

                $weather_row = [$city->name]; // Указываем первым столбцом название города
                for ($j = 1; $j < count($this_weather); $j++) {
                    if (key($this_weather) == 'temp_min' ||// Пропускаю те ключи что не хочу выводить.
                        key($this_weather) == 'temp_max') {
                        next($this_weather);
                    } elseif (key($this_weather) == 'date') { // Правлю вывод даты
                        array_push($weather_row, date('d.m.Y', strtotime(current($this_weather))));
                        next($this_weather);
                    } elseif (key($this_weather) == 'created_at') {// Правлю вывод таймстапа
                        array_push($weather_row, date('d.m.Y \в H:i:s', strtotime(current($this_weather))));
                        next($this_weather);
                    } else {
                        array_push($weather_row, current($this_weather));
                        next($this_weather);
                    }
                }
                $rows[] = $weather_row;
            }
        }
        if (isset($rows)) {
            $data[] = $rows;
        } else $data = [];

        return $data;
    }
}
