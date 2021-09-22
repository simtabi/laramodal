<?php

namespace Simtabi\Laramodal\Components\Livewire;

use Livewire\Component;
use Simtabi\Laranail\Traits\HasLivewireEvents;
use ReflectionClass;

class Laramodal extends Component
{

    use HasLivewireEvents;

    public ?string $activeModal = null;
    public array   $components  = [];
    public array   $args        = [];
    protected      $listeners   = [
        'openModal'  => 'openModal',
        'hideModal'  => 'hideModal',
        'resetModal' => 'resetModal',
    ];

    public function getComponentMethod($method)
    {

        if (empty($this->activeModal)) {
            return false;
        }

        $namespace = app('livewire')->getClass($this->activeModal);
        if (method_exists($namespace, $method)) {
           return $namespace::$method();
        }

        return '';
    }

    public function getModalSize($args)
    {
        $size = $this->getComponentMethod('modalSize');
        return !empty($size) ? $size : ($args['size'] ?? 'lg');
    }

    public function getModalHeading($args)
    {
        $subHeading = $this->getComponentMethod('modalTitle');
        return !empty($subHeading) ? $subHeading : ($args['subHeading'] ?? '');
    }

    public function getModalSubHeading($args)
    {
        $heading = $this->getComponentMethod('modalSubTitle');
        return !empty($heading) ? $heading : ($args['heading'] ?? '');
    }

    public function openModal($modal, $args = []) {

        $this->resetErrorBag();
        $this->resetValidation();

        $this->activeModal        = $modal;
        $this->args               = $args;
        $this->components[$modal] = [
            'name'       => $modal,
            'heading'    => $this->getModalHeading($args),
            'subHeading' => $this->getModalSubHeading($args),
            'size'       => $this->getModalSize($args),
            'args'       => $args,
        ];

        $this->emit('showModal', $modal);

    }

    public function closeModal(): void
    {
        $this->emit('hideModal');
    }

    public function closeModalWithEvents(array $events): void
    {
        $this->closeModal();
        $this->emitModalEvents($events);
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


    public function mount()
    {

    }

    public function __construct()
    {
        $this->initComponent();
        parent::__construct();
    }

    public function render()
    {
        return view('laramodal::livewire.laramodal');
    }
}
