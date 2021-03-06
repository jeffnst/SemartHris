<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 *
 * @see https://gajimu.com/main/pekerjaan-yanglayak/kompensasi/upah-lembur
 */
class HolidayCalculator extends Calculator
{
    /**
     * @param OvertimeInterface $overtime
     */
    public function calculate(OvertimeInterface $overtime): void
    {
        if (!$overtime->isHoliday()) {
            throw new CalculatorException();
        }

        if (5 === $this->workday) {
            $overtime->setCalculatedValue($this->calculateFiveDay($overtime));
        } else {
            $overtime->setCalculatedValue($this->calculateSixDay($overtime));
        }
    }

    /**
     * @param OvertimeInterface $overtime
     *
     * @return float
     */
    private function calculateFiveDay(OvertimeInterface $overtime): float
    {
        $hours = $this->getOvertimeHours($overtime);
        //8 first hour multiply with 2
        $calculatedValue = 8 * 2;
        $hours -= 8;
        if (0 < $hours) {
            --$hours;
            if (0 < $hours) {//Next 1 hour multiply with 3
                $calculatedValue += (3 * 1);
                $calculatedValue += (4 * $hours); //Others, multiply with 4
            } else {
                $calculatedValue += (3 * $hours);
            }
        }

        return $calculatedValue;
    }

    /**
     * @param OvertimeInterface $overtime
     *
     * @return float
     */
    private function calculateSixDay(OvertimeInterface $overtime): float
    {
        $hours = $this->getOvertimeHours($overtime);
        if (5 === $overtime->getOvertimeDate()->format('N')) {
            //5 first hour multiply with 2
            $calculatedValue = 5 * 2;
            $hours -= 5;
        } else {
            //7 first hour multiply with 2
            $calculatedValue = 7 * 2;
            $hours -= 7;
        }

        if (0 < $hours) {
            --$hours;
            if (0 < $hours) {//Next 1 hour multiply with 3
                $calculatedValue += (3 * 1);
                $calculatedValue += (4 * $hours); //Others, multiply with 4
            } else {
                $calculatedValue += (3 * $hours);
            }
        }

        return $calculatedValue;
    }
}
