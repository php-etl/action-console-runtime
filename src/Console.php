<?php

declare(strict_types=1);

namespace Kiboko\Component\Runtime\Action;

use Kiboko\Component\Action\ActionState;
use Kiboko\Contract\Action\ActionInterface;
use Kiboko\Contract\Action\ExecutingActionInterface;
use Kiboko\Contract\Action\StateInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

final class Console implements ActionRuntimeInterface
{
    private readonly Action $state;

    public function __construct(
        ConsoleOutput $output,
        private readonly ExecutingActionInterface $action,
        ?Action $state = null
    ) {
        $this->state = $state ?? new Action($output, 'A', 'Action');
    }

    public function execute(
        ActionInterface $action,
        StateInterface $state,
    ): self {
        $this->action->execute($action, $state = new ActionState($state));

        $this->state->addMetric('state', $state->observeState());

        return $this;
    }

    public function run(int $interval = 1000): int
    {
        $this->state->update();

        return 1;
    }
}
