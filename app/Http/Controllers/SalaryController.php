<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getCsv(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4|integer|min:' . date('Y'),
        ]);

        $year = $request->input('year');

        $results = [];
        for ($i = 1; $i <= 12; $i++) {

            $month = $i;
            if ($i < 10) {
                $month = '0' . $i;
            }
            $date = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-01');

            $results[] = [
                'month' => $date->monthName,
                'salaryDay' => (new Salary())->payDate($year, $i)->format('Y-m-d'),
                'bonusDate' => (new Bonus())->payDate($year, $i)->format('Y-m-d'),
            ];
        }

        try {
            return $this->download($results);
        } catch (\Exception $e) {
            abort(500);
        }
    }

    /**
     * @param $results
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
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
