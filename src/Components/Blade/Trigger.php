<?php

namespace Simtabi\Laramodal\Components\Blade;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Trigger extends Component
{

    public string  $component;
    public string  $size     = 'lg';
    public ?string $heading;
    public ?string $subHeading;
    public array   $args;
    public bool    $selfCall = false;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($component, string $heading = null, string $subHeading = null, string $size = 'lg', bool $selfCall = false, array $args = [])
    {
        $this->component  = $component;
        $this->size       = $size;
        $this->heading    = $heading;
        $this->subHeading = $subHeading;
        $this->args       = $args;
        $this->selfCall   = $selfCall;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('laramodal::components.trigger');
    }

}
