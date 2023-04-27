<?php

namespace App\Services\Models;

use ProcessMaker\Nayra\Bpmn\ActivityTrait;
use ProcessMaker\Nayra\Contracts\Bpmn\CallActivityInterface;

/**
 * CallActivity 类表示 BPMN 中的调用活动。调用活动是一种特殊的活动，用于调用另一个流程定义的实例。
 * 在 ProcessMaker Nayra 中，可以使用调用活动来表示子流程或者引用另一个流程。
 */

class CallActivity implements CallActivityInterface
{
    use ActivityTrait;

    /**
     * 始化对象属性，设置被调用的流程元素
     */
    public function __construct()
    {
        $this->setProperties([
            'calledElement' => '',
        ]);
    }

    /**
     * 设置被调用的流程元素
     * @param $calledElement
     * @return void
     */
    public function setCalledElement($calledElement): void
    {
        $this->setProperty('calledElement', $calledElement);
    }

    /**
     * 获取被调用的流程元素
     * @return mixed|\ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface
     */
    public function getCalledElement(): mixed
    {
        return $this->getProperty('calledElement');
    }

    /**
     * 这个示例中，我们返回一个空数组，表示没有关联的 BPMN 事件类。
     * 您可以根据需要修改此方法以返回与 CallActivity 相关的事件类。
     * @return array
     */
    public function getBpmnEventClasses()
    {
        return [];
    }
}

