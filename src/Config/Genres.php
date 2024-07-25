<?php

namespace App\Config;

enum Genres: string
{
    case Rock = 'Rock';
    case Pop = 'Pop';
    case Techno = 'Techno';
    case HipHop = 'Hip Hop';
    case Grunge = 'Grunge';
    case Ninetees = "90's";
    case Eightees = "80's";
    case Seventees = "70's";
    case Mixed = "Mixed";
}