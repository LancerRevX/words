@use('App\Enums\EntryClass')

<div class="p-4">
    <h1 class="mb-4 text-center text-xl">
        @if ($entry->id)
            Edit entry
        @else
            New entry
        @endif
    </h1>

    @session('status')
        <div class="bg-green-100 p-4">
            {{ $value }}
        </div>
    @endsession

    @if ($errors->any())
        <div class="mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="font-bold text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col gap-4">
        <div class="flex gap-4">
            <label for="language">Language</label>
            <select class="flex-1 rounded border border-gray-400 bg-white" id="language" name=""
                wire:model="language_id">
                @foreach ($this->languages as $language)
                    <option value="{{ $language->id }}">{{ Str::ucfirst($language->name) }} </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-4">
            <label for="class">Class</label>
            <select class="flex-1 rounded border border-gray-400 bg-white" id="class" name=""
                wire:model="class">
                @foreach (EntryClass::cases() as $case)
                    <option value="{{ $case->value }}">{{ $case->name }} </option>
                @endforeach
            </select>
        </div>
        <div class="">
            <label class="block" for="spelling">Spelling</label>
            <input
                class="@error('spelling') outline-2 outline-red-600 @enderror w-full rounded border border-gray-400 bg-white"
                id="spelling" name="" type="text" wire:model="spelling">
        </div>
        <div>
            <label class="block" for="pronounciation">Pronounciation</label>
            <input class="w-full rounded border border-gray-400 bg-white" id="pronounciation" name=""
                type="text" wire:model="pronounciation">
        </div>

        @foreach ($definitions as $i => $definition)
            @if (!$definition['to_delete'])
                <article class="flex flex-col rounded border-2 border-orange-600 bg-orange-200 p-2"
                    wire:key="{{ $i }}">
                    <header class="relative flex items-start">
                        <h2 class="flex-1">Definition #{{ $loop->iteration }}</h2>
                        <button class="absolute right-0 top-0 rounded border-2 border-gray-400 bg-gray-200 px-2 py-1"
                            wire:click="removeDefinition({{ $i }})"
                            wire:confirm="Are you sure?">Remove</button>
                    </header>
                    <div>
                        <label for="text">Text</label>
                        <textarea
                            class="@error("definitions.$i.text") outline-2 outline-red-600 @enderror w-full rounded border border-gray-400 bg-white"
                            id="text" wire:model="definitions[{{ $i }}].text" rows="3"></textarea>
                    </div>
                    <div>
                        <label for="image-url">Image url</label>
                        <input class="w-full rounded border border-gray-400 bg-white" id="image-url" name=""
                            type="text" wire:model="definitions[{{ $i }}].image_url">
                    </div>
                    <div class="flex gap-4">
                        <label for="source">Source</label>
                        <select class="flex-1 rounded border border-gray-400 bg-white" id="source" name=""
                            wire:model.number="definitions.{{ $i }}.source_id">
                            <option value="">...</option>
                            @foreach ($this->sources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (!empty($definition['examples']))
                        @foreach ($definition['examples'] as $j => $example)
                            @if (!$example['to_delete'])
                                <article class="mb-2 rounded border-2 border-purple-600 bg-purple-200 p-2">
                                    <header class="relative">
                                        <h3>Example #{{ $loop->iteration }}</h3>
                                        <button
                                            class="absolute right-0 top-0 rounded border-2 border-gray-400 bg-gray-200 px-2 py-1"
                                            wire:click="removeExample({{ $i }}, {{ $j }})"
                                            wire:confirm="Are you sure?">Remove</button>
                                    </header>
                                    <div>
                                        <label for="text">Text</label>
                                        <textarea
                                            class="@error("definitions.$i.examples.$j.text") outline-2 outline-red-600 @enderror w-full rounded border border-gray-400 bg-white"
                                            id="text" rows=3 wire:model="definitions.{{ $i }}.examples.{{ $j }}.text"></textarea>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    @endif
                    <button class="self-center rounded border-2 border-purple-600 bg-purple-200 px-4 py-2"
                        wire:click="addExample({{ $i }})">Add example</button>
                </article>
            @endif
        @endforeach
        <button class="rounded border-2 border-orange-600 bg-orange-200 px-4 py-2" type="button"
            wire:click="addDefinition">New definition</button>

        <button class="rounded border-2 border-green-600 bg-green-200 px-4 py-2" wire:click="save">Save</button>

        @if (isset($entry->id))
            <button class="rounded border-2 border-red-600 bg-red-200 px-4 py-2" wire:click="delete"
                wire:confirm="Are you sure?">Delete</button>
        @endif
    </div>
</div>
