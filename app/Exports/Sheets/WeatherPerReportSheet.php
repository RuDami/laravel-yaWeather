<?php

namespace App\Exports\Sheets;

use App\Weather;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class WeatherPerReportSheet implements FromQuery, WithTitle
{
    private $city;
    private $report;

    public function __construct($report, $city)
    {
        $this->city = $city;
        $this->report = $report;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Weather
            ::query()
            ->where('report_id', $this->report->id)
            ->where('city_id', $this->city->id);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Город ' . $this->city->name;
    }


}
