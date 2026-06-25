<?php

namespace App\Livewire;

use App\Models\Entry;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class Entries extends Component
{
    public Collection $entries;

    #[Url()]
    public string $query;

    public function mount() {
        $this->entries = Entry::all();
    }

    public function update() {
        if (!empty($this->query)) {
            $this->entries = Entry::whereLike('spelling', "%{$this->query}%")->get();
        } else {
            $this->entries = Entry::all();
        }
    }

    public function render()
    {
        return view('livewire.entries');
    }
}
