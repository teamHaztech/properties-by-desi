<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'sold';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available',
            self::Reserved => 'Reserved',
            self::Sold => 'Sold',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Available => 'green',
            self::Reserved => 'yellow',
            self::Sold => 'red',
        };
    }
}
