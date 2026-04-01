<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Lead $lead,
        public User $assignedBy,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'lead_phone' => $this->lead->phone,
            'lead_source' => $this->lead->source->label(),
            'assigned_by' => $this->assignedBy->name,
            'message' => "New lead \"{$this->lead->name}\" ({$this->lead->phone}) assigned to you by {$this->assignedBy->name}.",
        ];
    }
}
