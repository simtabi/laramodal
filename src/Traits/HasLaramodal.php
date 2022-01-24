<?php

namespace Simtabi\Laramodal\Traits;

trait HasLaramodal
{

    public ?string $activeModal = null;
    public array   $components  = [];

    public function closeModal(): self
    {
        $this->emit('hideModal');

        return $this;
    }

    public function closeModalWithEvents(array $events): self
    {
        $this->closeModal();
        $this->emitModalEvents($events);

        return $this;
    }

    public function resetState(): self
    {
        $this->activeModal = null;
        $this->components  = [];

        return $this;
    }

    public function resetModal(): self
    {
        $this->reset();

        return $this;
    }

    private function emitModalEvents(array $events): self
    {
        foreach ($events as $component => $event) {
            if (is_array($event)) {
                [$event, $params] = $event;
            }

            if (is_numeric($component)) {
                $this->emit($event, ...$params ?? []);
            } else {
                $this->emitTo($component, $event, ...$params ?? []);
            }
        }

        return $this;
    }

}
