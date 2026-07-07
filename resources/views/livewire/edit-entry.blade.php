@use('App\Enums\EntryClass')

<main>
    @session('back_url')
        <a class="block border-b-2 border-gray-300  bg-white text-gray-400 font-bold px-2 py-2" href="{{ $value }}">
            &lt; Back
        </a>
    @endsession
    <div class="p-4">
        @session('status')
            <div class="bg-green-100 p-4 mb-4">
                {{ $value }}
            </div>
        @endsession

        @if ($errors->any())
            <div class="mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="font-normal p-4 mb-4 bg-red-100">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="mb-4 text-center text-xl">
            @if ($entry->id)
                Edit entry
            @else
                New entry
            @endif
        </h1>
    
        <div class="flex gap-4 mb-4 items-center">
            <label for="language">Language</label>
            <select class="flex-1 px-2 py-1 rounded border border-gray-400 bg-white" id="language" name=""
                wire:model="language_id">
                @foreach ($this->languages as $language)
                    <option value="{{ $language->id }}">{{ Str::ucfirst($language->name) }} </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-4 items-center mb-4">
            <label for="class">Class</label>
            <select class="flex-1 py-1 px-2 rounded border border-gray-400 bg-white" id="class" name=""
                wire:model="class">
                @foreach (EntryClass::cases() as $case)
                    <option value="{{ $case->value }}">{{ $case->name }} </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1" for="spelling">Spelling</label>
            <input
                class="@error('spelling') outline-2 outline-red-600 @enderror w-full rounded border border-gray-400 bg-white px-2 py-1"
                id="spelling" name="" type="text" wire:model="spelling">
        </div>
        <div class="mb-4">
            <label class="block mb-1" for="pronounciation">Pronounciation</label>
            <input class="w-full rounded border py-1 px-2 border-gray-400 bg-white" id="pronounciation" name=""
                type="text" wire:model="pronounciation">
        </div>
    
        @foreach ($definitions as $i => $definition)
            @if (!$definition['to_delete'])
                <article class="rounded border-0 border-orange-600 bg-orange-200 p-4 mb-4"
                    wire:key="{{ $i }}">
                    <header class="relative flex items-start">
                        <h2 class="flex-1">Definition #{{ $loop->iteration }}</h2>
                        <button class="absolute right-0 top-0 rounded border-2 border-gray-400 bg-gray-200 px-2 py-1"
                            wire:click="removeDefinition({{ $i }})"
                            wire:confirm="Are you sure?">Remove</button>
                    </header>
                    <div class="mb-4">
                        <label for="text">Text</label>
                        <textarea
                            class="@error("definitions.$i.text") outline-2 outline-red-600 @enderror w-full rounded border block border-gray-400 bg-white px-2 py-1"
                            id="text" wire:model="definitions[{{ $i }}].text" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block" for="image-url">Image url</label>
                        <input class="@error("definitions.$i.image_url") outline-2 outline-red-600 @enderror w-full px-2 py-1 rounded border border-gray-400 bg-white" id="image-url" name=""
                            type="text" wire:model="definitions[{{ $i }}].image_url">
                    </div>
                    <div class="flex gap-4 mb-4 items-center">
                        <label for="source">Source</label>
                        <select class="py-1 px-2 flex-1 rounded border border-gray-400 bg-white" id="source" name=""
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
                                <article class="mb-4 rounded border-0 border-purple-600 bg-purple-200 p-4">
                                    <header class="relative">
                                        <h3>Example #{{ $loop->iteration }}</h3>
                                        <button
                                            class="absolute right-0 top-0 rounded border-2 border-gray-400 bg-gray-200 px-2 py-1"
                                            wire:click="removeExample({{ $i }}, {{ $j }})"
                                            wire:confirm="Are you sure?">Remove</button>
                                    </header>
                                    <div class="mb-4">
                                        <label class="block" for="text">Text</label>
                                        <textarea
                                            class="@error("definitions.$i.examples.$j.text") outline-2 outline-red-600 @enderror w-full rounded border border-gray-400 bg-white py-1 px-2 block"
                                            id="text" rows=3 wire:model="definitions.{{ $i }}.examples.{{ $j }}.text"></textarea>
                                    </div>
                                    <div class="flex gap-4 items-center">
                                        <label for="source">Source</label>
                                        <select class="px-2 py-1 flex-1 rounded border border-gray-400 bg-white" id="source"
                                            name=""
                                            wire:model.number="definitions.{{ $i }}.examples.{{ $j }}.source_id">
                                            <option value="">...</option>
                                            @foreach ($this->sources as $source)
                                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    @endif
                    <div class="flex justify-center">
                        <button class="self-center rounded border-2 border-purple-600 bg-purple-200 px-4 py-2"
                            wire:click="addExample({{ $i }})">Add example</button>
                    </div>
                </article>
            @endif
        @endforeach
        <button class="rounded block w-full mb-4 border-2 border-orange-600 bg-orange-200 px-4 py-2" type="button"
            wire:click="addDefinition">New definition</button>
    
        <button class="rounded border-2 block w-full mb-4 border-green-600 bg-green-200 px-4 py-2" wire:click="save">Save</button>
    
        @if (isset($entry->id))
            <button class="rounded border-2 block w-full border-red-600 bg-red-200 px-4 py-2" wire:click="delete"
                wire:confirm="Are you sure?">Delete</button>
        @endif
    </div>
</main>
