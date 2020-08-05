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
     */
    private $writerType = Excel::CSV;

    /**
     * @return array
     */
    /*  public function sheets(): array
      {
          $sheets = [];
          $cities = $this->report->cities()->get();

          foreach ($cities as $city) {

              $sheets[] = new WeatherPerReportSheet($this->report, $city);
          }

          return $sheets;
      }*/
    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function array(): array
    {
        $cities = $this->report->cities()->get();

        $head_row = ["Города/Даты"];
        $rows[] = &$head_row;
        foreach ($cities as $city) {
            $row = [$city->name];
            $weather = Weather::where('report_id', $this->report->id)->where('city_id', $city->id)->get();
            for ($i = 0; $i < count($weather); $i++) { // Заупскаем цикл по колонкам погоды
                $this_weather = $weather[$i];
                array_push($row,
                    "Статус:" . $this_weather->status . ";\r" .
                    "Состояние:" . $this_weather->condition . ";\r" .
                    "Температура:" . $this_weather->temp . "°;\r" .
                    "Влажность:" . $this_weather->humidity . "%;\r");

                $head_row[$i + 1] = date('d.m.Y', strtotime($weather[$i]->date));
            }
            $rows[] = $row;
        }
        if (isset($rows)) {
            $data[] = $rows;
        } else $data = [];

        return $data;
    }
}
