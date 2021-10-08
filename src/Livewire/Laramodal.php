<?php

namespace Simtabi\Laramodal\Livewire;

use Livewire\Component;
use Simtabi\Laramodal\Traits\HasLaramodal;
use Simtabi\Laranail\Traits\HasLivewireEvents;
use ReflectionClass;

class Laramodal extends Component
{

    use HasLivewireEvents;
    use HasLaramodal;

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
        $size = $this->getComponentMethod('modalSize');
        return !empty($size) ? $size : ($data['size'] ?? 'lg');
    }

    public function getModalHeading($data)
    {
        $subHeading = $this->getComponentMethod('modalTitle');
        return !empty($subHeading) ? $subHeading : ($data['subHeading'] ?? '');
    }

    public function getModalSubHeading($data)
    {
        $heading = $this->getComponentMethod('modalSubTitle');
        return !empty($heading) ? $heading : ($data['heading'] ?? '');
    }

}
