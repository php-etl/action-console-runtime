<?php

declare(strict_types=1);

namespace Kiboko\Component\Runtime\Action;

use Kiboko\Contract\Action\ActionInterface;
use Kiboko\Contract\Action\RejectionInterface;
use Kiboko\Contract\Action\RunnableInterface;
use Kiboko\Contract\Action\StateInterface;

interface ActionRuntimeInterface extends RunnableInterface
{
    public function execute(
        ActionInterface $action,
        RejectionInterface $rejection,
        StateInterface $state
    ): self;
}
