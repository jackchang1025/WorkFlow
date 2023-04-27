<?php

namespace App\Services\Tasks;

use ProcessMaker\Nayra\Bpmn\ActivityTrait;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;

class FormActivityTask implements ServiceTaskInterface
{
    use ActivityTrait;

    const TAG_NAME = 'formActivityTask';

    /**
     * 返回一个数组，该数组定义了 BPMN 元素的自定义事件类。
     * @return array
     */
    protected function getBpmnEventClasses(): array
    {
        return[];
    }

    /**
     * 设置服务任务的实现。实现可以是一个可调用的结构（如闭包、函数名或类方法名）
     * @param mixed $implementation
     * @return FormActivityTask
     */
    public function setImplementation(mixed $implementation): static
    {
        return $this;
    }

    /**
     * 获取服务任务的实现。实现可以是一个可调用的结构（如闭包、函数名或类方法名）
     * @return mixed
     */
    public function getImplementation(): mixed
    {
        return null;
    }

    /**
     * 执行服务任务。首先尝试执行服务任务实现（通过 executeService() 方法），如果执行成功，调用 complete() 方法完成任务；否则，将令牌设置为失败状态
     * @param TokenInterface $token
     * @return GetLotteryDataTask|$this
     * @throws \Throwable
     */
    public function run(TokenInterface $token): GetLotteryDataTask|static
    {

        if ($data = $this->executeService($token)) {

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
     * @return int[]
     * @throws \Throwable
     */
    private function executeService(TokenInterface $token): array
    {
        $dataStore = $token->getInstance()->getDataStore();

        throw_if(empty($data = $this->getFormData()), new \Exception('表单数据不能为空'));

        // 在这里实现您的远程请求接口逻辑，例如发送 HTTP 请求
        $dataStore->putData('issue', $data);

        return $dataStore->getData('issue');
    }

    private function getFormData()
    {
        // 获取扩展元素中的表单数据
        $bpmnElement = $this->getBpmnElement();

        $formDataElement = $bpmnElement->getElementsByTagNameNS('http://example.com/form', 'formData')->item(0);

        $inputFields = $formDataElement->getElementsByTagNameNS('http://example.com/form', 'input');
        $selectFields = $formDataElement->getElementsByTagNameNS('http://example.com/form', 'select');


        $data = [];
        foreach ($inputFields as $inputField) {
            $data[$inputField->getAttribute('id')] = ['value' => $inputField->getAttribute('value')];
        }

        // 解析选择字段
        foreach ($selectFields as $selectField) {
            $options = [];
            $optionElements = $selectField->getElementsByTagNameNS('http://example.com/form', 'option');
            foreach ($optionElements as $optionElement) {
                $options[] = [
                    'value' => $optionElement->getAttribute('value'),
                    'check' => $optionElement->getAttribute('check'),
                ];
            }

            $data[$selectField->getAttribute('id')] = ['value' => $options];
        }

        return $data;
    }
}
