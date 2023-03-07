<?php declare(strict_types=1);

namespace Kiboko\Component\Runtime\Action;

use Kiboko\Contract\Pipeline\ActionInterface;
use Kiboko\Contract\Pipeline\RejectionInterface;
use Kiboko\Contract\Pipeline\RunnableInterface;
use Kiboko\Contract\Pipeline\StateInterface;

interface ActionRuntimeInterface extends RunnableInterface
{
    public function execute(
        ActionInterface $action,
        RejectionInterface $rejection,
        StateInterface $state
    ): ActionRuntimeInterface;
}
