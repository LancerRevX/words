<?php

namespace App\Livewire;

use App\Enums\EntryClass;
use App\Models\Definition;
use App\Models\Entry;
use App\Models\Example;
use App\Models\Language;
use App\Models\Source;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class EditEntry extends Component
{
    public ?Entry $entry = null;

    public int $language_id;

    public EntryClass $class = EntryClass::Noun;

    public string $spelling = '';

    public ?string $pronounciation = null;

    public array $definitions;

    public function mount()
    {
        if ($this->entry) {
            $this->fill($this->entry->only('spelling', 'pronounciation', 'class', 'language_id'));
            $this->definitions = $this->entry->definitions()->with('examples')->get()->toArray();
            foreach ($this->definitions as &$definition) {
                $definition['to_delete'] = false;
                foreach ($definition['examples'] as &$example) {
                    $example['to_delete'] = false;
                }
            }
        } else {
            $this->language_id = $this->languages->first()->id;
            $this->entry = new Entry;
        }
    }

    public function save()
    {
        $this->validate(
            [
                'language_id' => ['required', 'exists:languages,id'],
                'class' => ['required', Rule::enum(EntryClass::class)],
                'spelling' => ['required'],
                'definitions' => ['array', 'min:1'],
                'definitions.*.to_delete' => ['required', 'boolean'],
                'definitions.*.text' => ['exclude_if:definitions.*.to_delete,true', 'required'],
                'definitions.*.source_id' => ['exclude_if:definitions.*.to_delete,true', 'nullable', 'integer:strict', 'exists:sources,id'],
                'definitions.*.examples' => ['array'],
                'definitions.*.examples.*.text' => ['exclude_if:definitions.*.examples.*.to_delete,true', 'required'],
            ],
            [
                'definitions' => [
                    'min' => 'Please, provide at least one definition.',
                ],
                'definitions.*.text' => 'Definitions\' text fields are required.',
                'definitions.*.examples.*.text' => 'Examples\' text fields are required.',
            ],
        );

        $this->entry->fill($this->only('class', 'spelling', 'pronounciation'));
        $this->entry->language()->associate(Language::findOrFail($this->language_id));
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

        session()->flash('status', 'Entry successfully saved.');
        // $this->redirect(route('home'));
    }

    public function delete() {
        if (!isset($this->entry->id)) {
            return;
        }

        $this->entry->delete();
        $this->redirect(route('home'));
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
    public function languages()
    {
        return Language::all();
    }

    #[Computed]
    public function sources()
    {
        return Source::all();
    }
}
