<?php

namespace App\Services\Models\GateWay;

use App\Services\Bpmn\CustomConditionedExclusiveTransition;
use ProcessMaker\Nayra\Bpmn\Collection;
use ProcessMaker\Nayra\Bpmn\DefaultTransition;
use ProcessMaker\Nayra\Bpmn\ExclusiveGatewayTrait;
use ProcessMaker\Nayra\Bpmn\Models\ExclusiveGateway;
use ProcessMaker\Nayra\Bpmn\State;
use ProcessMaker\Nayra\Contracts\Bpmn\FlowInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\GatewayInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TransitionInterface;

class ExclusiveGatewayService extends ExclusiveGateway
{
    use ExclusiveGatewayTrait;

    /**
     * 使用自定义条件类构建条件连接
     *
     * @param FlowInterface $targetFlow
     * @param callable $condition
     * @param bool $default
     *
     * @return $this
     */
    protected function buildConditionedConnectionTo(FlowInterface $targetFlow, callable $condition, $default = false): static
    {
        $outgoingPlace = new State((array)$this, (array)GatewayInterface::TOKEN_STATE_INCOMING);
        if ($default) {
            $outgoingTransition = $this->setDefaultTransition(new DefaultTransition((array)$this));
        } else {
            $outgoingTransition = $this->conditionedTransition(
                new CustomConditionedExclusiveTransition((array)$this),
                $condition
            );
        }
        $outgoingTransition->attachEvent(TransitionInterface::EVENT_AFTER_CONSUME, function (TransitionInterface $transition, Collection $consumedTokens) {
            foreach ($consumedTokens as $token) {
                $this->getRepository()
                    ->getTokenRepository()
                    ->persistGatewayTokenPassed($this, $token);
            }

            $this->notifyEvent(GatewayInterface::EVENT_GATEWAY_TOKEN_PASSED, (array)$this, (array)$transition, (array)$consumedTokens);
        });
        $this->transition->connectTo($outgoingPlace);
        $outgoingPlace->connectTo($outgoingTransition);
        $outgoingTransition->connectTo($targetFlow->getTarget()->getInputPlace($targetFlow));

        return $this;
    }
}
