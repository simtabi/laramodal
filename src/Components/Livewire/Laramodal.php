<?php

namespace Simtabi\Laramodal\Components\Livewire;

use Livewire\Component;
use Simtabi\Laranail\Traits\HasLivewireEvents;

class Laramodal extends Component
{

    use HasLivewireEvents;

    public function __construct()
    {
        $this->initComponent();
    }

    public function render()
    {
        return view('laramodal::livewire.modal');
    }

}