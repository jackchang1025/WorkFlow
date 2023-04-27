<?php

namespace App\Services\Tasks;

use App\Services\Events\ServiceTaskActivatedEvent;
use ProcessMaker\Nayra\Bpmn\ActivityTrait;
use ProcessMaker\Nayra\Bpmn\Models\ServiceTask;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;

class GetLotteryDataTask extends ServiceTask
{
    use ActivityTrait;

    const TAG_NAME = 'getLotteryDataTask';

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
        dump('getLotteryDataTask run');

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

        /**
         * @var \Illuminate\Database\Eloquent\Model $model
         */
        $model = $dataStore->getData('model');

        $option = ['单', '双'];

        $model->lottery_rule .= $option[rand(0, 1)];

        $model->lottery_conut_rule += 1;

        $winLost = rand(0, 1);

        $model->win_lose_rule .= $winLost;

        if ($winLost) {
            $model->total_amount_rule += $model->bet_amount_rule ?? 0;
        } else {
            $model->total_amount_rule -= $model->bet_amount_rule ?? 0;
        }

        $dataStore->putData('model', $model);

        return true;
    }


}
