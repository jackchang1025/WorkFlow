<?php

namespace App\Services\Expressions;

use DateTime;

class DateExpression implements ExpressionInterface
{

    /**
     * @param string $expression
     * @param $dataStore
     * @return bool
     */
    public function evaluate(string $expression, $dataStore = null): bool
    {
        $regexpValidDate = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}[T ][0-9]{2}:[0-9]{2}(:[0-9]{2})?(?:[\+-][0-9]{2}:[0-9]{2}|Z)?/';

        return (bool)preg_match($regexpValidDate, $expression);
    }

    public function isExpression(string $expression): bool
    {
        try {
            return (bool)new DateTime($expression);
        } catch (\Exception) {
            return false;
        }
    }
}
