<?php

namespace App\Services\Tasks;

use App\Services\Events\ServiceTaskActivatedEvent;
use ProcessMaker\Nayra\Bpmn\ActivityTrait;
use ProcessMaker\Nayra\Bpmn\Models\ServiceTask;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;

class BetTask extends ServiceTask
{
    use ActivityTrait;

    const TAG_NAME = 'betTask';

    /**
     * 返回一个数组，该数组定义了 BPMN 元素的自定义事件类。
     * @return array
     */
    protected function getBpmnEventClasses(): array
    {
        return [
            ServiceTaskInterface::EVENT_SERVICE_TASK_ACTIVATED => ServiceTaskActivatedEvent::class,
        ];
    }


    /**
     * 执行服务任务。首先尝试执行服务任务实现（通过 executeService() 方法），如果执行成功，调用 complete() 方法完成任务；否则，将令牌设置为失败状态
     * @param TokenInterface $token
     * @return GetLotteryDataTask|$this
     */
    public function run(TokenInterface $token): GetLotteryDataTask|static
    {
        dump('betTask run');

        if ($this->executeService($token)) {

            $this->complete($token);

        } else {
            // 如果请求失败，将令牌设置为失败状态
            $token->setStatus(ActivityInterface::TOKEN_STATE_FAILING);
        }
        return $this;
    }

    /**
     * 服务任务执行器，用于执行服务任务的实现。这里的实现仅用于测试目的。这个方法尝试调用服务任务的实现，如果调用成功返回 true，否则返回 false。
     * @param TokenInterface $token
     * @return bool
     */
    private function executeService(TokenInterface $token): bool
    {
        // 在这里实现您的远程请求接口逻辑，例如发送 HTTP 请求

        $dataStore = $token->getInstance()->getDataStore();

        $model = $dataStore->getData('model');

        $model->bet_lottery_rule += 1;

        $model->bet_tatal_code_rule .= $model->bet_code_rule;

        return true;
    }


}
