<?php

namespace App\Services\Expressions;

interface ExpressionInterface
{
    public function evaluate(string $expression, $dataStore = null): bool;

    public function isExpression(string $expression): bool;
}
