<?php

namespace Simtabi\Laramodal\Traits;

trait HasLaramodal
{

    public function closeModal(): void
    {
        $this->emit('hideModal');
    }

    public function closeModalWithEvents(array $events): void
    {
        $this->closeModal();
        $this->emitModalEvents($events);
    }

    public function resetState()
    {
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->reset();
    }

    private function emitModalEvents(array $events): void
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
    }

}
