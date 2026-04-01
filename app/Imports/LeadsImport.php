<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;
    public int $skipped = 0;
    public int $duplicates = 0;
    public array $errors = [];

    protected ?int $defaultAgentId;

    public function __construct(?int $defaultAgentId = null)
    {
        $this->defaultAgentId = $defaultAgentId;
    }

    public function collection(Collection $rows)
    {
        $validSources = ['call', 'whatsapp', 'instagram', 'facebook', 'referral', 'website', 'walk_in', 'other'];
        $validStatuses = ['new', 'contacted', 'spoken', 'interested', 'not_interested', 'visited_site', 'follow_up_required', 'loan_processing', 'closed_won', 'closed_lost'];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because heading row is 1

            $name = trim($row['name'] ?? $row['client_name'] ?? $row['lead_name'] ?? $row['customer_name'] ?? '');
            $phone = $this->cleanPhone($row['phone'] ?? $row['mobile'] ?? $row['phone_number'] ?? $row['contact'] ?? '');
            $email = trim($row['email'] ?? $row['email_address'] ?? '');
            $source = strtolower(trim($row['source'] ?? $row['lead_source'] ?? 'other'));
            $status = strtolower(trim($row['status'] ?? $row['lead_status'] ?? 'new'));
            $budgetMin = $this->cleanNumber($row['budget_min'] ?? $row['budget'] ?? $row['min_budget'] ?? null);
            $budgetMax = $this->cleanNumber($row['budget_max'] ?? $row['max_budget'] ?? null);
            $propertyType = trim($row['property_type'] ?? $row['preferred_property_type'] ?? $row['type'] ?? '');
            $location = trim($row['location'] ?? $row['location_preference'] ?? $row['area'] ?? '');
            $urgency = strtolower(trim($row['urgency'] ?? 'medium'));

            // Skip empty rows
            if (empty($name) && empty($phone)) {
                continue;
            }

            // Validate required fields
            if (empty($name)) {
                $this->errors[] = "Row {$rowNum}: Name is required.";
                $this->skipped++;
                continue;
            }

            if (empty($phone)) {
                $this->errors[] = "Row {$rowNum}: Phone is required for \"{$name}\".";
                $this->skipped++;
                continue;
            }

            // Check duplicate
            if (Lead::where('phone', $phone)->exists()) {
                $this->duplicates++;
                $this->skipped++;
                continue;
            }

            // Normalize source
            $source = str_replace([' ', '-'], '_', $source);
            if (!in_array($source, $validSources)) {
                $source = 'other';
            }

            // Normalize status
            $status = str_replace([' ', '-'], '_', $status);
            if (!in_array($status, $validStatuses)) {
                $status = 'new';
            }

            // Normalize urgency
            if (!in_array($urgency, ['low', 'medium', 'high', 'immediate'])) {
                $urgency = 'medium';
            }

            // If only one budget value, use it for both
            if ($budgetMin && !$budgetMax) {
                $budgetMax = $budgetMin;
            }

            Lead::create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?: null,
                'source' => $source,
                'status' => $status,
                'assigned_agent_id' => $this->defaultAgentId,
                'budget_min' => $budgetMin,
                'budget_max' => $budgetMax,
                'preferred_property_type' => $propertyType ?: null,
                'location_preference' => $location ?: null,
                'urgency' => $urgency,
            ]);

            $this->imported++;
        }
    }

    protected function cleanPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9+]/', '', trim($phone));

        // Remove +91 prefix
        if (str_starts_with($phone, '+91')) {
            $phone = substr($phone, 3);
        } elseif (str_starts_with($phone, '91') && strlen($phone) === 12) {
            $phone = substr($phone, 2);
        }

        return $phone;
    }

    protected function cleanNumber($value): ?float
    {
        if ($value === null || $value === '') return null;

        $value = preg_replace('/[^0-9.]/', '', (string) $value);

        return $value !== '' ? (float) $value : null;
    }
}
