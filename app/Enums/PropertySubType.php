<?php

namespace App\Enums;

enum PropertySubType: string
{
    case Orchard = 'orchard';
    case Settlement = 'settlement';
    case Sanad = 'sanad';
    case NA = 'na';

    public function label(): string
    {
        return match ($this) {
            self::Orchard => 'Orchard',
            self::Settlement => 'Settlement',
            self::Sanad => 'Sanad',
            self::NA => 'N/A (Non-Agricultural)',
        };
    }
}
