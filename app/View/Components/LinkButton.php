<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LinkButton extends Component
{
    
    public $href;
    public $text;
    public $classes;

    // Constructor to accept attributes
    public function __construct($href, $text, $classes = 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded')
    {
        $this->href = $href;
        $this->text = $text;
        $this->classes = $classes; // Tailwind classes for styling
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.link-button');
    }
}
