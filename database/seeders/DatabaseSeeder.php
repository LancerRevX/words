<?php

namespace Database\Seeders;

use App\Enums\EntryClass;
use App\Models\Entry;
use App\Models\Language;
use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $english = Language::create(['name' => 'english']);

        $cambridge = Source::create(['name' => 'Cambridge Dictionary']);

        $entry = Entry::make(['spelling' => 'a bolt from the blue', 'class' => EntryClass::Idiom]);
        $entry->language()->associate($english)->save();
        $definition = $entry->definitions()->make([
            'text' => 'something important or unusual that happens suddenly or unexpectedly',
        ]);
        $definition->source()->associate($cambridge)->save();
        $definition->examples()->make([
            'text' => 'The resignation of the chairman came like a bolt from the blue.',
        ])->source()->associate($cambridge)->save();

        $entry = Entry::make([
            'spelling' => 'foxglove', 'class' => EntryClass::Noun,
            'pronounciation' => 'ˈfɑːks.ɡlʌv',
        ])->language()->associate($english);
        $entry->save();
        $definition = $entry->definitions()->make([
            'text' => 'a tall, thin plant with white, yellow, pink, red, or purple bell-shaped flowers growing all the way up its stem',
            'image_url' => 'https://dictionary.cambridge.org/images/full/foxglo_noun_002_14825.jpg?version=6.0.78',
        ])->source()->associate($cambridge);
        $definition->save();
        $definition->examples()->make([
            'text' => 'Some of the herbs and many of the flowers, like the foxglove, are still an important source of some of our most valuable medicines.',
        ])->source()->associate($cambridge)->save();
    }
}
