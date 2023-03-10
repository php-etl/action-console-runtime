<?php declare(strict_types=1);

namespace Kiboko\Component\Runtime\Action;

use Kiboko\Component\State;
use Kiboko\Contract\Pipeline\ActionInterface;
use Kiboko\Contract\Pipeline\ExecutingActionInterface;
use Kiboko\Contract\Pipeline\RejectionInterface;
use Kiboko\Contract\Pipeline\StateInterface;
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
        RejectionInterface $rejection,
        StateInterface $state,
    ): self {
        $this->action->execute($action, $rejection, $state = new State\MemoryState($state));

        $this->state
            ->addMetric('read', $state->observeAccept())
            ->addMetric('error', fn () => 0)
            ->addMetric('rejected', $state->observeReject());

        return $this;
    }

    public function run(int $interval = 1000): int
    {
        $line = 0;
        foreach ($this->action->walk() as $item) {
            if ($line++ % $interval === 0) {
                $this->state->update();
            }
        };
        $this->state->update();

        return $line;
    }
}
