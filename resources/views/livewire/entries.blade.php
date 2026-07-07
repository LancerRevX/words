@use('App\Enums\SortType')

<main class="flex flex-col p-4">
    <input class="mb-4 w-full rounded border border-gray-300 bg-white px-2" id="" name="" type="search"
        placeholder="search..." wire:model="query" wire:keyup.debounce="$refresh">
    <a class="mb-4 rounded border-2 border-green-600 bg-green-200 px-4 py-2 text-center" href="{{ route('new') }}">New
        entry</a>
    <div class="flex justify-evenly mb-4">
        @foreach (SortType::cases() as $case)
            <label
                class="@if ($sortType == $case) border-orange-600 bg-orange-200 @endif rounded-xl border-2 border-gray-400 bg-gray-100 px-4 py-2"
                for="{{ $case->value }}">{{ $case->name }}<input class="appearance-none" id="{{ $case->value }}"
                    type="radio" value="{{ $case->value }}" wire:model="sortType" wire:change="$refresh" />
            </label>
        @endforeach
    </div>
    @foreach ($this->entries as $entry)
        <a wire:click="edit({{ $entry->id }})">
            <article class="mb-4" wire:key="{{ $entry->id }}">
                <h2 class="mb-2">
                    <span class="font-semibold">{{ Str::ucfirst($entry->spelling) }}</span>
                    <span>({{ $entry->class }})</span>
                    @if ($entry->pronounciation)
                        <span>[{{ $entry->pronounciation }}]</span>
                    @endif
                </h2>
                @foreach ($entry->definitions as $definition)
                    @if ($definition->image_url)
                        <img class="float-left mr-4 w-1/3" src="{{ $definition->image_url }}"
                            alt="image of {{ $entry->spelling }}">
                    @endif
                    <p>{{ $definition->text }}@if ($definition->examples()->count())
                            :
                        @endif
                    </p>
                    @foreach ($definition->examples as $example)
                        <p class="italic">— {{ $example->text }}</p>
                    @endforeach
                @endforeach
            </article>
        </a>
    @endforeach
</main>
