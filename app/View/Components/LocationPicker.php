<?php

namespace App\View\Components;

use App\Location;
use Illuminate\View\Component;

class LocationPicker extends Component
{
    public $key;

    public $label;

    public $name;

    public $cooperativeId;

    public $value;
    public $selectedValue;

    public function __construct($label, $name, $cooperativeId, $value = null, $selectedValue=null)
    {
        $this->key = md5(time() . mt_rand());
        $this->label = $label;
        $this->name = $name;
        $this->cooperativeId = $cooperativeId;
        $this->value = $value;
        $this->selectedValue = $selectedValue;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {

        $locations = Location::where('cooperative_id', $this->cooperativeId)->get(['id', 'place_id', 'name']);
        return view('components.location-picker', compact('locations'));
    }
}
