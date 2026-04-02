# Properties By Desi — Voice Agent Integration Guide

## Overview
This document provides everything needed to integrate a voice AI agent with the Properties By Desi CRM system. The voice agent should be able to:
1. **Look up leads** by phone number or name
2. **Create new leads** from voice conversations
3. **Update lead status** after calls
4. **Log communications** (call summaries)
5. **Query properties** based on customer requirements
6. **Check available cities/locations**
7. **Schedule follow-ups**
8. **Check for duplicate leads**

---

## API Base URL
```
https://haztech.cloud/api/v1
```
Authentication: Bearer token (Laravel Sanctum)
```
Authorization: Bearer {token}
```

---

## DATABASE SCHEMA — Tables the Voice Agent Needs

### 1. `leads` — PRIMARY TABLE (Read + Write)
The core table. Voice agent creates and updates leads here.

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| id | bigint | auto | Primary key |
| name | varchar(255) | YES | Customer's full name |
| phone | varchar(20) | YES | Primary identifier — **always check for duplicates before creating** |
| email | varchar(255) | no | Customer email |
| source | enum | YES | How the lead came in. **For voice agent, use `'call'`** |
| status | enum | YES | Current pipeline stage (see values below) |
| assigned_agent_id | bigint | no | FK to `users.id` — **default to Pasad (id: 3)** |
| budget_min | decimal(12,2) | no | Minimum budget in INR (e.g., 3000000 = 30 Lakh) |
| budget_max | decimal(12,2) | no | Maximum budget in INR (e.g., 20000000 = 2 Crore) |
| preferred_property_type | varchar | no | `'plot'`, `'villa'`, or `'flat'` |
| location_preference | varchar | no | Free text — but prefer using `city_lead` table |
| urgency | enum | no | `'low'`, `'medium'`, `'high'`, `'immediate'` — default `'medium'` |
| is_converted | boolean | no | Whether lead became a client |
| last_contacted_at | timestamp | no | Update this after every call |
| created_at | timestamp | auto | |
| deleted_at | timestamp | auto | Soft delete — never hard delete |

**Lead Status Values (Pipeline Order):**
```
new → contacted → spoken → interested → visited_site → follow_up_required → loan_processing → closed_won
                                  ↘ not_interested → closed_lost
```

**Lead Source Values:**
```
call, whatsapp, instagram, facebook, referral, website, walk_in, other
```
> Voice agent should always use `source = 'call'`

---

### 2. `cities` — REFERENCE TABLE (Read Only)
115 Goa locations pre-loaded. Voice agent should match customer's spoken location to these.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | City/village name (unique) |
| state | varchar(255) | Always "Goa" |
| is_active | boolean | Only use active ones |

**Sample cities:** Panjim, Calangute, Anjuna, Vagator, Assagao, Siolim, Margao, Vasco da Gama, Porvorim, Mapusa, Dona Paula, Colva, Palolem, Morjim, Arambol, Candolim, Baga...

**Full city list:** 115 cities — query `SELECT id, name FROM cities WHERE is_active = 1 ORDER BY name`

---

### 3. `city_lead` — PIVOT TABLE (Write)
Maps a lead to multiple interested cities. Voice agent should populate this when customer mentions locations.

| Column | Type | Description |
|--------|------|-------------|
| city_id | bigint | FK to `cities.id` |
| lead_id | bigint | FK to `leads.id` |

**Example:** Customer says "I'm interested in Anjuna and Vagator"
```sql
INSERT INTO city_lead (city_id, lead_id) VALUES
(5, {lead_id}),   -- Anjuna (id: 5)
(6, {lead_id});   -- Vagator (id: 6)
```

---

### 4. `properties` — REFERENCE TABLE (Read Only)
Voice agent should query this to suggest properties matching customer requirements.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| title | varchar(255) | e.g., "Premium Orchard Plot - Assagao" |
| type | enum | `'plot'`, `'villa'`, `'flat'` |
| sub_type | enum | Plot-specific: `'orchard'`, `'settlement'`, `'sanad'`, `'na'` |
| location | varchar(255) | City/area name |
| area | varchar(255) | Broader area (e.g., "North Goa") |
| price | decimal(14,2) | Price in INR |
| size_sqm | decimal(10,2) | Size in square meters |
| size_label | varchar(255) | Human readable (e.g., "500 sq.m") |
| bedrooms | int | For villa/flat |
| bathrooms | int | For villa/flat |
| status | enum | `'available'`, `'reserved'`, `'sold'` |
| description | text | Full property description |

**Matching query example** — Customer wants plots in Anjuna under 1 Crore:
```sql
SELECT id, title, location, price, size_label, sub_type
FROM properties
WHERE type = 'plot'
  AND location LIKE '%Anjuna%'
  AND price <= 10000000
  AND status = 'available'
  AND deleted_at IS NULL;
```

---

### 5. `lead_property` — PIVOT TABLE (Write)
When voice agent suggests properties to customer, record it here.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | auto |
| lead_id | bigint | FK to `leads.id` |
| property_id | bigint | FK to `properties.id` |
| status | enum | `'suggested'`, `'shown'`, `'visited'`, `'interested'`, `'rejected'` |
| feedback | text | Customer's verbal feedback about the property |
| shown_at | timestamp | When property was presented |
| visited_at | timestamp | When customer visited site |

---

### 6. `communications` — CALL LOG TABLE (Write)
**Voice agent MUST log every call here.**

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| id | bigint | auto | |
| lead_id | bigint | YES | FK to `leads.id` |
| user_id | bigint | YES | The agent — **use 3 (Pasad) or 1 (Admin) for voice agent** |
| type | enum | YES | **Always `'call'` for voice agent** |
| direction | enum | YES | `'inbound'` or `'outbound'` |
| summary | text | YES | **AI-generated call summary** |
| duration_minutes | int | no | Call duration |
| created_at | timestamp | auto | |

---

### 7. `notes` — NOTES TABLE (Write)
For storing important details from conversations.

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| id | bigint | auto | |
| notable_type | varchar | YES | Always `'App\\Models\\Lead'` |
| notable_id | bigint | YES | The lead ID |
| user_id | bigint | YES | Use 1 (Admin) for voice agent system notes |
| content | text | YES | The note text |
| is_pinned | boolean | no | Set to 1 for important notes |

---

### 8. `follow_ups` — SCHEDULE TABLE (Write)
Voice agent can schedule follow-ups based on conversation.

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| id | bigint | auto | |
| lead_id | bigint | YES | FK to `leads.id` |
| user_id | bigint | YES | Agent to follow up — default 3 (Pasad) |
| title | varchar(255) | YES | e.g., "Call back about Anjuna plot" |
| description | text | no | Details |
| scheduled_at | datetime | YES | When to follow up |
| status | enum | YES | `'pending'`, `'completed'`, `'missed'`, `'cancelled'` |
| priority | enum | no | `'low'`, `'medium'`, `'high'` |

---

### 9. `clients` — CONVERTED LEADS (Write — when deal closes)
When a lead becomes a buyer.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | auto |
| lead_id | bigint | FK to `leads.id` |
| name | varchar(255) | Same as lead name |
| phone | varchar(20) | Same as lead phone |
| buying_type | enum | `'loan'`, `'cash'`, `'black_white_mix'` |
| purpose | enum | `'investment'`, `'end_use'` |
| buyer_profile | enum | `'family'`, `'individual'`, `'company'` |
| urgency | enum | `'low'`, `'medium'`, `'high'`, `'immediate'` |
| pan_number | varchar(10) | KYC |
| aadhar_number | varchar(12) | KYC |

---

### 10. `users` — AGENTS (Read Only)
Voice agent needs to know who to assign leads to.

| ID | Name | Username | Role |
|----|------|----------|------|
| 1 | Admin | admin | admin |
| 2 | Rahul Sharma | rahul | manager |
| 3 | **Pasad** | **pasad** | **sales_agent** (default) |
| 4 | Amit Desai | amit | sales_agent |
| 5 | Sneha Patel | sneha | sales_agent |
| 6 | Fiza | fiza | admin |
| 7 | Mohsin | mohsin | super_admin |
| 8 | Mufeez | mufeez | super_admin |

---

## VOICE AGENT WORKFLOWS

### Workflow 1: Incoming Call — New Lead
```
1. Get caller phone number
2. CHECK DUPLICATE: SELECT * FROM leads WHERE phone = '{phone}' AND deleted_at IS NULL
3. IF EXISTS → Load existing lead data, update last_contacted_at, skip to step 6
4. IF NEW → Create lead:
   INSERT INTO leads (name, phone, source, status, assigned_agent_id, urgency, budget_min, budget_max, preferred_property_type)
   VALUES ('{name}', '{phone}', 'call', 'new', 3, 'medium', 3000000, 20000000, '{type}')
5. Attach cities:
   INSERT INTO city_lead (city_id, lead_id) VALUES ({city_id}, {lead_id})
6. Log the call:
   INSERT INTO communications (lead_id, user_id, type, direction, summary, duration_minutes)
   VALUES ({lead_id}, 3, 'call', 'inbound', '{ai_summary}', {duration})
7. Update lead:
   UPDATE leads SET status = 'contacted', last_contacted_at = NOW() WHERE id = {lead_id}
8. If follow-up needed:
   INSERT INTO follow_ups (lead_id, user_id, title, scheduled_at, status, priority)
   VALUES ({lead_id}, 3, '{title}', '{datetime}', 'pending', 'medium')
```

### Workflow 2: Property Recommendation
```
1. Get lead requirements (budget, type, location)
2. Query matching properties:
   SELECT * FROM properties
   WHERE type = '{type}'
     AND price BETWEEN {budget_min} AND {budget_max}
     AND status = 'available'
     AND deleted_at IS NULL
     AND (location IN (SELECT c.name FROM cities c JOIN city_lead cl ON c.id = cl.city_id WHERE cl.lead_id = {lead_id}))
3. Present to customer
4. Log which were suggested:
   INSERT INTO lead_property (lead_id, property_id, status, shown_at)
   VALUES ({lead_id}, {property_id}, 'suggested', NOW())
5. Record feedback:
   UPDATE lead_property SET feedback = '{customer_feedback}', status = 'interested'
   WHERE lead_id = {lead_id} AND property_id = {property_id}
```

### Workflow 3: Status Update After Call
```
Customer interested → UPDATE leads SET status = 'interested' WHERE id = {lead_id}
Customer not interested → UPDATE leads SET status = 'not_interested' WHERE id = {lead_id}
Wants to visit → UPDATE leads SET status = 'visited_site' WHERE id = {lead_id}
Needs time → UPDATE leads SET status = 'follow_up_required' WHERE id = {lead_id}
Applying for loan → UPDATE leads SET status = 'loan_processing' WHERE id = {lead_id}
Deal done → UPDATE leads SET status = 'closed_won', is_converted = 1 WHERE id = {lead_id}
```

---

## API ENDPOINTS (Already Built)

All endpoints require `Authorization: Bearer {token}` header.

### Leads
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/leads` | List leads (supports ?search=, ?status=, ?source=, ?assigned_agent_id=) |
| POST | `/api/v1/leads` | Create lead |
| GET | `/api/v1/leads/{id}` | Get lead with all relations |
| PUT | `/api/v1/leads/{id}` | Update lead |
| DELETE | `/api/v1/leads/{id}` | Soft delete lead |
| PATCH | `/api/v1/leads/{id}/status` | Update status only (body: `{"status": "interested"}`) |

### Properties
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/properties` | List properties (supports ?type=, ?status=, ?price_min=, ?price_max=, ?search=) |
| GET | `/api/v1/properties/{id}` | Get property details |

### Dashboard
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/dashboard/stats` | Overview stats, source performance, agent performance |

---

## IMPORTANT BUSINESS RULES

1. **Phone is the primary identifier** — always check for duplicates before creating
2. **Default agent is Pasad (id: 3)** — assign all voice-generated leads to him unless specified
3. **Default source for voice agent is `'call'`**
4. **Budget is in INR** — 1 Lakh = 100000, 1 Crore = 10000000
5. **Default budget range: 30 Lakh (3000000) to 2 Crore (20000000)**
6. **Always log the call** in `communications` table after every conversation
7. **Always update `last_contacted_at`** on the lead after a call
8. **Goa-specific property types:**
   - Plot → Orchard (agricultural), Settlement (residential), Sanad (government grant), NA (non-agricultural)
   - Villa → Standalone houses with garden/pool
   - Flat → Apartments
9. **Soft deletes** — never hard delete, always use `deleted_at`
10. **Indian currency formatting:** Display as Lakhs (L) and Crores (Cr)

---

## TRAINING DATA — Sample Conversations

### New Lead Call:
> "Hi, I'm looking for a plot in Anjuna or Vagator. My budget is around 50 lakhs to 1 crore. I want a settlement plot, not orchard."

**Extract:**
- name: (ask)
- phone: (from caller ID)
- source: call
- preferred_property_type: plot
- budget_min: 5000000
- budget_max: 10000000
- cities: [Anjuna (5), Vagator (6)]
- Note: "Interested in settlement plots only, not orchard"

### Returning Lead:
> "I called earlier about the villa in Vagator. I visited last week and I'm interested. Can we discuss the price?"

**Actions:**
- Look up lead by phone
- Update status: interested
- Update lead_property: status = 'visited', visited_at = last week
- Log communication with summary
- Schedule follow-up for price negotiation

---

## QUICK REFERENCE — City Name → ID Mapping (Most Popular)

| City | ID | City | ID |
|------|----|------|----|
| Panjim | 1 | Anjuna | 5 |
| Mapusa | 2 | Vagator | 6 |
| Calangute | 3 | Assagao | 7 |
| Candolim | 4 | Siolim | 8 |
| Morjim | 9 | Arambol | 10 |
| Porvorim | 12 | Margao | 23 |
| Vasco da Gama | 24 | Colva | 25 |
| Palolem | 27 | Dona Paula | 21 |
| Baga | 47 | Arpora | 45 |

---

## GETTING API TOKEN

```bash
# Create a token for the voice agent
php artisan tinker --execute="
  \$user = App\Models\User::find(1);
  echo \$user->createToken('voice-agent')->plainTextToken;
"
```

Use this token in all API requests:
```
Authorization: Bearer {token}
```
