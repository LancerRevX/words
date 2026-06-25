<?php

namespace App\Livewire;

use App\Models\Entry;
use Livewire\Component;

class EditEntry extends Component
{
    public bool $isNewEntry;
    public ?Entry $entry;

    public function render()
    {
        return view('livewire.edit-entry');
    }
}
