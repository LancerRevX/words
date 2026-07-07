<?php

namespace App\Livewire;

use App\Enums\SortType;
use App\Models\Entry;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class Entries extends Component
{
    #[Url(except: '')]
    public string $query = '';

    #[Url(except: SortType::AToZ)]
    public SortType $sortType = SortType::AToZ;

    #[Computed]
    public function entries() {
        if (!empty($this->query)) {
            $builder = Entry::whereLike('spelling', "%{$this->query}%");
        } else {
            $builder = Entry::select();
        }
        switch ($this->sortType) {
            case SortType::AToZ:
                $builder->orderBy('spelling', 'asc');
                break;
            case SortType::ZToA:
                $builder->orderBy('spelling', 'desc');
                break;
            case SortType::Old:
                $builder->orderBy('created_at', 'asc');
                break;
            case SortType::New:
                $builder->orderBy('created_at', 'desc');
                break;
        }
        return $builder->get();
    }

    public function edit(int $id) {
        $entry = Entry::findOrFail($id);
        $queryParams = ['sortType' => $this->sortType];
        if (!empty($this->query)) {
            $queryParams['query'] = $this->query;
        }
        session()->put('back_url', route('home', $queryParams));
        redirect(route('edit', ['entry' => $entry]));
    }

    public function render()
    {
        return view('livewire.entries');
    }
}
