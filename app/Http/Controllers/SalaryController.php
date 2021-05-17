<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{

    public function getCsv(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4|integer|min:' . date('Y'),
        ]);

        $year = $request->input('year');

        $results = [];
        for ($i = 1; $i <= 12; $i++) {

            if ($i < 10) {
                $i = '0' . $i;
            }
            $date = Carbon::createFromFormat('Y-m-d', $year . '-' . $i . '-01');

            $results[] = $this->calculateSalaryBonus($date);
        }

        return $this->download($results);
    }

    private function calculateSalaryBonus(Carbon $date): array
    {

        // Calculate salary day
        $endOfMonth = $date->endOfMonth();
        $endOfMonthWeekDay = $endOfMonth->dayOfWeek;
        if ($endOfMonthWeekDay == 6 || $endOfMonthWeekDay == 7) {
            $salaryDate = $endOfMonth::parse('last friday of ' . $date->monthName . ' ' . $date->year);
        } else {
            $salaryDate = $date;
        }

        // Calculate bonus day
        $bonusDate = (new Carbon($date))->day(15);
        if ($bonusDate->dayOfWeek == 6 || $bonusDate->dayOfWeek == 7) {
            $bonusDate = $bonusDate->parse('next wednesday');
        }

        return [
            'month' => $date->monthName,
            'salaryDay' => $salaryDate->format('Y-m-d'),
            'bonusDate' => $bonusDate->format('Y-m-d'),
        ];
    }

    private function download($results)
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=salaries.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $list = $results;

        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

}
