<?php

namespace App\Services\Bpmn;

use ProcessMaker\Nayra\Bpmn\ConditionedExclusiveTransition;
use ProcessMaker\Nayra\Contracts\Bpmn\FlowInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;

class CustomConditionedExclusiveTransition extends ConditionedExclusiveTransition
{
    /**
     * 自定义验证方法
     *
     * @param ExecutionInstanceInterface $instance
     * @return bool
     */
    protected function customValidation(ExecutionInstanceInterface $instance): bool
    {
        $instance->getBpmnElement();
    }

    /**
     * 覆盖默认条件判断行为，使用自定义验证方法
     *
     * @param TokenInterface|null $token
     * @param ExecutionInstanceInterface|null $executionInstance
     * @return bool
     */
    public function assertCondition(TokenInterface $token = null, ExecutionInstanceInterface $executionInstance = null): bool
    {
        $condition = $this->customValidation($executionInstance);

        if ($token->getId() === 'Flow_0gyacca') {
            return $condition;
        } elseif ($token->getId() === 'Flow_1t9xm15') {
            return !$condition;
        }

        return false;
    }
}
