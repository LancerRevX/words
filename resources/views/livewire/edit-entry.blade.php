<div class="p-4">
    <h1 class="text-center mb-4 text-xl">
        @if (is_null($entry))
            New entry
        @else
            Edit entry
        @endif
    </h1>

    <form action="">
        <label for="spelling">Spelling</label>
        <input type="text" name="" id="spelling" class="bg-white border border-gray-400 rounded">
        <label for="pronounciation">Pronounciation</label>
        <input type="text" name="" id="pronounciation" class="bg-white border border-gray-400 rounded">
    </form>
</div>
