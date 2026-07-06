<?php

namespace App\Livewire;

use App\Enums\EntryClass;
use App\Models\Definition;
use App\Models\Entry;
use App\Models\Example;
use App\Models\Source;
use Livewire\Attributes\Computed;
use Livewire\Component;

class EditEntry extends Component
{
    public ?Entry $entry = null;

    public string $spelling = '';

    public ?string $pronounciation = null;

    public EntryClass $class = EntryClass::Noun;

    public array $definitions;

    public function mount()
    {
        if ($this->entry) {

            $this->fill($this->entry->only('spelling', 'pronounciation', 'class'));
            $this->definitions = $this->entry->definitions()->with('examples')->get()->toArray();
            foreach ($this->definitions as &$definition) {
                $definition['to_delete'] = false;
                foreach ($definition['examples'] as &$example) {
                    $example['to_delete'] = false;
                }
            }
        } else {
            $this->entry = new Entry;

        }
    }

    public function save()
    {
        $this->validate([
            'definitions' => ['array', 'min:1'],
            'definitions.*.to_delete' => ['required', 'boolean'],
            'definitions.*.text' => ['exclude_if:definitions.*.to_delete,true', 'required'],
            'definitions.*.examples.*.text' => ['required'],
        ]);

        $this->entry->fill($this->only('class', 'spelling', 'pronounciation'));
        $this->entry->save();

        foreach ($this->definitions as $definitionArray) {
            if ($definitionArray['to_delete'] && $definitionArray['id'] !== null) {
                Definition::destroy($definitionArray['id']);

                continue;
            }

            $definition =
                $this->entry->definitions()->find($definitionArray['id']) ?? $this->entry->definitions()->make();
            $definition->fill($definitionArray);
            $definition->save();

            foreach ($definitionArray['examples'] as $exampleArray) {
                if ($exampleArray['to_delete'] && $exampleArray['id'] !== null) {
                    Example::destroy($exampleArray['id']);

                    continue;
                }

                $example = $definition->examples()->find($exampleArray['id']) ?? $definition->examples()->make();
                $example->fill($exampleArray);
                $example->save();
            }
        }

        session()->flash('status', 'Post successfully updated.');
        // $this->redirect(route('home'));
    }

    public function addDefinition()
    {
        $this->definitions[] = [
            'id' => null,
            'text' => '',
            'source_id' => $this->sources->first()->id,
            'examples' => [],
            'to_delete' => false,
        ];
    }

    public function addExample(int $definitionKey)
    {
        $this->definitions[$definitionKey]['examples'][] = [
            'id' => null,
            'text' => '',
            'source_id' => null,
            'to_delete' => false,
        ];
    }

    public function removeDefinition(int $key)
    {
        $this->definitions[$key]['to_delete'] = true;
    }

    public function removeExample(int $definitionKey, int $exampleKey)
    {
        $this->definitions[$definitionKey]['examples'][$exampleKey]['to_delete'] = true;
    }

    public function render()
    {
        return view('livewire.edit-entry');
    }

    #[Computed]
    public function sources()
    {
        return Source::all();
    }
}
