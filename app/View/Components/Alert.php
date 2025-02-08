<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Create a new component instance.
     */
    public $type;
    public $message;
    public $dismissable;

    public function __construct($type, $message, $dismissable = false)
    {
        $this->type = $type;
        $this->message = $message;
        $this->dismissable = $dismissable;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
