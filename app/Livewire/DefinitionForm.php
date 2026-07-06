<?php

namespace App\Livewire;

use App\Models\Definition;
use App\Models\Entry;
use Livewire\Component;

class DefinitionForm extends Component
{
    public Entry $entry;
    public ?Definition $definition = null;

    public int $number;
    public string $text = '';

    public function mount() {
        if ($this->definition === null) {
            $this->definition = new Definition();
            $this->definition->entry()->associate($this->entry);
        } else {
            $this->fill($this->definition->only('text'));
        }

    }

    public function render()
    {
        return view('livewire.definition-form');
    }
}
