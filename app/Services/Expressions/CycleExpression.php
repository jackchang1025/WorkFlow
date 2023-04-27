<?php

namespace App\Services\Expressions;

use DateInterval;
use DateTime;
use ProcessMaker\Nayra\Bpmn\Models\DatePeriod;

class CycleExpression implements ExpressionInterface
{

    public function evaluate(string $expression, $dataStore = null): bool
    {
        try {
            //Improve Repeating intervals (R/start/interval/end) configuration

            $response = preg_match('/^R\/([^\/]+)\/([^\/]+)\/([^\/]+)$/', $expression, $repeating)
                ? new DatePeriod(new DateTime($repeating[1]), new DateInterval($repeating[2]), new DateTime($repeating[3]))
                : new DatePeriod($expression);

            return (bool) $response;

        } catch (\Exception $e) {

            return false;
        }

    }

    /**
     * @param string $expression
     * @return bool
     * @throws \Exception
     */
    public function isExpression(string $expression): bool
    {
        try {
            return (bool) new DatePeriod($expression);
        } catch (\Exception $e) {
            return false;
        }
    }
}
