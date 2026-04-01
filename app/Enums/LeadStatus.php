<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Spoken = 'spoken';
    case Interested = 'interested';
    case NotInterested = 'not_interested';
    case VisitedSite = 'visited_site';
    case FollowUpRequired = 'follow_up_required';
    case LoanProcessing = 'loan_processing';
    case ClosedWon = 'closed_won';
    case ClosedLost = 'closed_lost';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Contacted => 'Contacted',
            self::Spoken => 'Spoken',
            self::Interested => 'Interested',
            self::NotInterested => 'Not Interested',
            self::VisitedSite => 'Visited Site',
            self::FollowUpRequired => 'Follow-up Required',
            self::LoanProcessing => 'Loan Processing',
            self::ClosedWon => 'Closed Won',
            self::ClosedLost => 'Closed Lost',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'blue',
            self::Contacted => 'indigo',
            self::Spoken => 'purple',
            self::Interested => 'yellow',
            self::NotInterested => 'red',
            self::VisitedSite => 'teal',
            self::FollowUpRequired => 'orange',
            self::LoanProcessing => 'cyan',
            self::ClosedWon => 'green',
            self::ClosedLost => 'gray',
        };
    }

    public static function pipelineStatuses(): array
    {
        return [
            self::New,
            self::Contacted,
            self::Spoken,
            self::Interested,
            self::VisitedSite,
            self::FollowUpRequired,
            self::LoanProcessing,
            self::ClosedWon,
        ];
    }
}
