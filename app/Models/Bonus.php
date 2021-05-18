<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Traits\Creator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
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

        // Calculate bonus day
        $bonusDate = (new Carbon($date))->day(15);
        if ($bonusDate->dayOfWeek == 6 || $bonusDate->dayOfWeek == 7) {
            $bonusDate = $bonusDate->parse('next wednesday');
        }

        $this->payDate = $bonusDate;

        return $this->payDate;
    }

}
