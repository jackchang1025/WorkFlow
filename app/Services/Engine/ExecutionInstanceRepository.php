<?php

namespace App\Services\Engine;


use ProcessMaker\Nayra\Contracts\Bpmn\ParticipantInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Contracts\Repositories\ExecutionInstanceRepositoryInterface;
use ProcessMaker\Nayra\Contracts\Repositories\StorageInterface;
use ProcessMaker\Nayra\Engine\ExecutionInstance;

/**
 * 这个接口提供了两个方法，分别是启动流程和结束流程要做出的操作，
 * persistInstanceCreated（ExecutionInstanceInterface $instance）是启动流程时执行的，
 * persistInstanceCompleted（ExecutionInstanceInterface $instance）是结束流程时执行的方法，
 * 它的参数中可以获取到bpmn文件的processid为$instance->getProcess()->getId()，
 * 在这两个方法中我们需要做出自己的数据库操作，比如写入用户的申请，和修改用户申请的状态为完成。
 *

 */

class ExecutionInstanceRepository implements ExecutionInstanceRepositoryInterface
{
    private array $instances = [];


    /**
     * 加载执行实例
     * @param $uid
     * @param StorageInterface $storage
     * @return mixed|ExecutionInstanceInterface|null
     */
    public function loadExecutionInstanceByUid($uid, StorageInterface $storage)
    {
        // 仅通过 getExecutionInstance() 方法搜索存储库。
        // 您可以根据需要实现从数据库或其他持久性存储中加载执行实例的逻辑。
        return $this->getExecutionInstance($uid);
    }

    /**
     * 创建执行实例
     * @return ExecutionInstance
     */
    public function createExecutionInstance(): ExecutionInstance
    {
        // 创建一个新的 ExecutionInstance 对象并返回。
        // 根据需要实现自定义执行实例对象的创建。
        return new ExecutionInstance();
    }

    /**
     * 持久化实例创建
     * @param ExecutionInstanceInterface $instance
     * @return void
     */
    public function persistInstanceCreated(ExecutionInstanceInterface $instance): void
    {
        // 在这个示例中，我们仅将实例添加到内存存储库。
        // 您可以根据需要实现将实例保存到数据库或其他持久性存储的逻辑。
        $this->instances[$instance->getId()] = $instance;
    }

    /**
     * 持久化实例完成
     * @param ExecutionInstanceInterface $instance
     * @return void
     */
    public function persistInstanceCompleted(ExecutionInstanceInterface $instance): void
    {
        // 在这个示例中，我们仅从内存存储库中删除实例。
        // 您可以根据需要实现将实例从数据库或其他持久性存储中删除的逻辑。
        unset($this->instances[$instance->getId()]);
    }

    /**
     * 持久化实例协作
     * @param ExecutionInstanceInterface $target
     * @param ParticipantInterface $targetParticipant
     * @param ExecutionInstanceInterface $source
     * @param ParticipantInterface $sourceParticipant
     * @return null
     */
    public function persistInstanceCollaboration(ExecutionInstanceInterface $target, ParticipantInterface $targetParticipant, ExecutionInstanceInterface $source, ParticipantInterface $sourceParticipant)
    {
        // 在这个示例中，我们不执行任何操作，因为我们没有实现协作逻辑。
        // 您可以根据需要实现执行实例之间协作的逻辑。
        return null;
    }

    /**
     * 获取执行实例
     * @param $uid
     * @return mixed|null
     */
    public function getExecutionInstance($uid): mixed
    {
        return $this->instances[$uid] ?? null;
    }

}
