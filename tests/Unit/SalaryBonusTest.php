<?php

namespace Tests\Unit;

use App\Models\Bonus;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class SalaryBonusTest extends TestCase
{
    /**
     * Salary & Bonus Feature Tests.
     *
     * @return void
     */
    public function test_normal_salary_month()
    {
        $salary = (new Salary())->payDate(2021, 6);

        $this->assertEquals('2021-06-30', $salary->format('Y-m-d'));
    }

    public function test_exception_salary_month()
    {
        $salary = (new Salary())->payDate(2021, 7);

        $this->assertEquals('2021-07-30', $salary->format('Y-m-d'));
    }

    public function test_normal_bonus_month()
    {
        $bonus = (new Bonus())->payDate(2021, 4);

        $this->assertEquals('2021-04-15', $bonus->format('Y-m-d'));
    }

    public function test_exception_bonus_month()
    {
        $bonus = (new Bonus())->payDate(2021, 5);

        $this->assertEquals('2021-05-19', $bonus->format('Y-m-d'));
    }

}
