<?php

namespace Simtabi\Laramodal\Livewire;

use Livewire\Component;
use Simtabi\Laranail\Traits\HasLivewireEvents;
use Pheg;

class Laramodal extends Component
{

    use HasLivewireEvents;

    protected $listeners   = [
        'openModal'  => 'openModal',
        'resetModal' => 'resetModal',
    ];

    public function __construct()
    {
        $this->initComponent();
    }

    public function mount()
    {

    }

    public function render()
    {
        return view('laramodal::livewire.laramodal');
    }

    public function getArgs(bool $asObject = true): array|object
    {
        return $asObject ? Pheg::fromAnyToStdObject($this->args) : $this->args;
    }

    public function openModal($modal, $args = [])
    {

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

    public function getComponentMethod($method)
    {

        if (empty($this->activeModal))
        {
            return false;
        }

        $namespace = app('livewire')->getClass($this->activeModal);
        if (method_exists($namespace, $method))
        {
            return $namespace::$method();
        }

        return '';
    }

    public function getModalSize($data)
    {
        return ($data['size'] ?? $this->getComponentMethod('getModalSize')) ?? 'lg';
    }

    public function getModalHeading($data)
    {
        return $data['heading'] ?? $this->getComponentMethod('getModalTitle');
    }

    public function getModalSubHeading($data)
    {
        return $data['subHeading'] ?? $this->getComponentMethod('getModalSubTitle');
    }

}
