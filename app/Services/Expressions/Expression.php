<?php

namespace App\Services\Expressions;

class Expression implements ExpressionInterface
{

    /**
     * @param string $expression
     * @param $dataStore
     * @return bool
     */
    public function evaluate(string $expression, $dataStore = null): bool
    {
        if ($this->isExpression($expression)) {

            // 使用正则表达式查找变量并替换为 $dataStore
            $replacedExpression = preg_replace('/\$[a-zA-Z_][a-zA-Z0-9_]*/', '$dataStore', $expression);

            // 使用 eval() 执行替换后的表达式

            try {

                return eval("return $replacedExpression;");

            } catch (\Exception | \Throwable) {

            }
        }
        return false;
    }

    /**
     * @param string $expression
     * @return bool|false
     */
    public function isExpression(string $expression): bool
    {
        // 检查字符串是否包含不安全的字符或函数
        $blacklist = ['eval', 'exec', 'system', 'passthru', 'shell_exec', ';'];
        foreach ($blacklist as $item) {
            if (str_contains($expression, $item)) {
                return false;
            }
        }

        // 检查字符串是否包含合法的 PHP 表达式
//        $pattern = '/^[\s\$a-zA-Z0-9_\(\)\{\}\[\]\-+*\/%.<>=!&|^@:,\x5C\x27\x22]*$/';
        $pattern = '/^[\s\$a-zA-Z0-9_\(\)\{\}\[\]\-+*\/%.<>=!&|^@:,]*$/';
        return (bool) preg_match($pattern, $expression);
    }
}
