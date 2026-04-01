<?php

namespace App\Enums;

enum PropertyType: string
{
    case Plot = 'plot';
    case Villa = 'villa';
    case Flat = 'flat';

    public function label(): string
    {
        return match ($this) {
            self::Plot => 'Plot',
            self::Villa => 'Villa',
            self::Flat => 'Flat',
        };
    }
}
