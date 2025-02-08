<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
class CategorySearch extends Component
{
    public $searchcat = '';
    public $categories = [];



    public function updatedsearchcat($searchcat)
    {

        if ($searchcat) {
            $this->categories = Category::where('name', 'like', '%' . $searchcat . '%')
                ->limit(10)
                ->get();
        } else {
            $this->categories = [];
        }
       
    }
    public function render()
    {
        return view('livewire.category-search');
    }
}
