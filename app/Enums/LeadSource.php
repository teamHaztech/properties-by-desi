<?php

namespace App\Enums;

enum LeadSource: string
{
    case Call = 'call';
    case WhatsApp = 'whatsapp';
    case Instagram = 'instagram';
    case Facebook = 'facebook';
    case Referral = 'referral';
    case Website = 'website';
    case WalkIn = 'walk_in';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Call => 'Phone Call',
            self::WhatsApp => 'WhatsApp',
            self::Instagram => 'Instagram',
            self::Facebook => 'Facebook',
            self::Referral => 'Referral',
            self::Website => 'Website',
            self::WalkIn => 'Walk-in',
            self::Other => 'Other',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Call => 'phone',
            self::WhatsApp => 'message-circle',
            self::Instagram => 'instagram',
            self::Facebook => 'facebook',
            self::Referral => 'users',
            self::Website => 'globe',
            self::WalkIn => 'map-pin',
            self::Other => 'more-horizontal',
        };
    }
}
