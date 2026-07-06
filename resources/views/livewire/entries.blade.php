<main class="p-4 flex flex-col">
    <input type="search" name="" id="" class="bg-white mb-4 border border-gray-300 rounded w-full px-2"
        placeholder="search..." wire:model="query" wire:keyup.debounce="update">
    <a href="{{ route('new') }}" class="border-2 text-center rounded mb-4 border-green-600 bg-green-200 px-4 py-2">New entry</a>
    @foreach ($entries as $entry)
        <a href="{{ route('edit', ['entry' => $entry]) }}">
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
                        <img src="{{ $definition->image_url }}" alt="image of {{ $entry->spelling }}"
                            class="w-1/3 float-left mr-4">
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
