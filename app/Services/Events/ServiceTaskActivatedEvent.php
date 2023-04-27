<?php

namespace App\Services\Events;

use ProcessMaker\Nayra\Bpmn\Models\ServiceTask;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;

class ServiceTaskActivatedEvent
{

    /**
     * ActivityActivatedEvent constructor.
     *
     * @param ServiceTask $activity
     * @param TokenInterface $token
     */
    public function __construct(public readonly ServiceTask $activity, public readonly TokenInterface $token)
    {
    }

    /**
     * @return ServiceTask
     */
    public function activatedEvent(): ServiceTask
    {
        return $this->activity->run($this->token);
    }
}
