<?php

namespace Simtabi\Laramodal\Components\Livewire;

use Livewire\Component;
use Simtabi\Laranail\Traits\HasLivewireEvents;

class Laramodal extends Component
{

    use HasLivewireEvents;

    public ?string $activeModal = null;
    public array   $args        = [];

    protected $listeners        = ['openModal', 'closeModal'];

    public function openModal($modal, $args = []) {
        $this->activeModal = $modal;
        $this->args        = $args;

        $this->dispatchBrowserEvent('modal-ready', ['modal' => $modal]);
    }

    public function closeModal() {
        $this->reset(['activeModal', 'args']);
    }


    public function __construct()
    {
        $this->initComponent();
    }

    public function render()
    {
        return view('laramodal::livewire.laramodal');
    }

}
