<?php

declare(strict_types=1);

namespace Kiboko\Component\Runtime\Action;

use Kiboko\Component\State;
use Kiboko\Contract\Action\ActionInterface;
use Kiboko\Contract\Action\ActionState;
use Kiboko\Contract\Action\ExecutingActionInterface;
use Kiboko\Contract\Action\StateInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

final class Console implements ActionRuntimeInterface
{
    private readonly State\StateOutput\Action $state;

    public function __construct(
        ConsoleOutput $output,
        private readonly ExecutingActionInterface $action,
        ?State\StateOutput\Action $state = null
    ) {
        $this->state = $state ?? new State\StateOutput\Action($output, 'A', 'Action');
    }

    public function execute(
        ActionInterface $action,
        StateInterface $state,
    ): self {
        $this->action->execute($action, $state = new ActionState($state));

        $this->state
            ->addMetric('read', $state->observeAccept())
            ->addMetric('error', fn () => 0)
            ->addMetric('rejected', $state->observeReject())
        ;

        return $this;
    }

    public function run(int $interval = 1000): int
    {
        $this->state->update();

        return 1;
    }
}
