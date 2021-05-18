<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    public $payDate;

    /**
     * @param $q
     * @param $year
     * @param $month
     *
     * @return Carbon
     */
    public function scopePayDate($q, $year, $month) : Carbon
    {
        $date = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-01');

        // Calculate salary day
        $endOfMonth = $date->endOfMonth();
        $endOfMonthWeekDay = $endOfMonth->dayOfWeek;
        if ($endOfMonthWeekDay == 6 || $endOfMonthWeekDay == 7) {
            $this->payDate = $endOfMonth::parse('last friday of ' . $date->monthName . ' ' . $date->year);
        } else {
            $this->payDate = $date;
        }

        return $this->payDate;
    }

}
