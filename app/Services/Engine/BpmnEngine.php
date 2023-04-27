<?php

namespace App\Services\Engine;

use ProcessMaker\Nayra\Contracts\Engine\EngineInterface;
use ProcessMaker\Nayra\Contracts\Engine\EventDefinitionBusInterface;
use ProcessMaker\Nayra\Contracts\Engine\JobManagerInterface;
use ProcessMaker\Nayra\Contracts\EventBusInterface;
use ProcessMaker\Nayra\Contracts\RepositoryInterface;
use ProcessMaker\Nayra\Engine\EngineTrait;

/**
 * Test implementation for EngineInterface.
 *
 * @package ProcessMaker\Bpmn
 */
class BpmnEngine implements EngineInterface
{
    /**
     * 中给出了核心方法，如流转方法 `runToNextState`，创建实例方法 `createExecutionInstance`，从已有数据中加载实例方法
     */
    use EngineTrait;


    /**
     *  用于存储和检索流程定义和实例
     * @var RepositoryInterface
     */
    private RepositoryInterface $repository;

    /**
     * 负责处理事件和消息
     * @var EventBusInterface $dispatcher
     */
    protected EventBusInterface $dispatcher;

    /**
     * Test engine constructor.
     *
     * @param RepositoryInterface $repository
     * @param EventBusInterface $dispatcher
     * @param JobManagerInterface|null $jobManager
     * @param EventDefinitionBusInterface|null $eventDefinitionBus
     */
    public function __construct(RepositoryInterface $repository, EventBusInterface $dispatcher, JobManagerInterface $jobManager = null, EventDefinitionBusInterface $eventDefinitionBus = null)
    {
        $this->setRepository($repository);
        $this->setDispatcher($dispatcher);
        $this->setJobManager($jobManager);
        $eventDefinitionBus && $this->setEventDefinitionBus($eventDefinitionBus);
    }

    /**
     * @return EventBusInterface
     */
    public function getDispatcher(): EventBusInterface
    {
        return $this->dispatcher;
    }

    /**
     * @param EventBusInterface $dispatcher
     *
     * @return $this
     */
    public function setDispatcher(EventBusInterface $dispatcher): static
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $factory
     *
     * @return $this
     */
    public function setRepository(RepositoryInterface $factory): static
    {
        $this->repository = $factory;
        return $this;
    }
}
