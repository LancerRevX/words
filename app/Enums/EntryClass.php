<?php

namespace App\Enums;

enum EntryClass: string
{
    case Noun = 'noun';
    case Adjective = 'adjective';
    case Verb = 'verb';
    case Idiom = 'idiom';
}
