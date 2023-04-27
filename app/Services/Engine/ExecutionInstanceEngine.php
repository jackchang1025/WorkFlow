<?php

namespace App\Services\Engine;

use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Engine\ExecutionInstanceTrait;

class ExecutionInstanceEngine implements ExecutionInstanceInterface
{
    use ExecutionInstanceTrait;

    protected function initToken()
    {
        $this->setId(uniqid());
    }

}
