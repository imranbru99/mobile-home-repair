<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(623) 555-0167',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@padlinerepair.example'],
            [
                'name' => 'Sofia Reyes',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(623) 555-0167',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'dispatch@padlinerepair.example'],
            [
                'name' => 'Marcus Hale',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(623) 555-0167',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Padline Mobile Repair',
            'company_phone' => '(623) 555-0167',
            'company_email' => 'hello@padlinerepair.example',
            'whatsapp' => '16235550167',
            'service_area' => 'Peoria, Glendale, Surprise & West Valley parks',
            'tax_rate' => '8.10',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Elastomeric Roof Coat (gal)', 'sku' => 'RF-EL-1', 'category' => 'supplies', 'unit_cost' => 38, 'unit_price' => 95, 'stock_qty' => 80],
            ['name' => 'Roof Patch Kit', 'sku' => 'RF-PK-1', 'category' => 'supplies', 'unit_cost' => 22, 'unit_price' => 65, 'stock_qty' => 60],
            ['name' => 'Subfloor Panel (sheet)', 'sku' => 'FL-SB-1', 'category' => 'supplies', 'unit_cost' => 48, 'unit_price' => 0, 'stock_qty' => 40],
            ['name' => 'Skirting Panel (section)', 'sku' => 'SK-PN-1', 'category' => 'supplies', 'unit_cost' => 35, 'unit_price' => 0, 'stock_qty' => 100],
            ['name' => 'Leveling Block Set', 'sku' => 'LV-BK-1', 'category' => 'supplies', 'unit_cost' => 28, 'unit_price' => 0, 'stock_qty' => 50],
            ['name' => 'Weather Seal Visit', 'sku' => 'WS-VT-1', 'category' => 'service', 'unit_cost' => 120, 'unit_price' => 380, 'stock_qty' => 999],
            ['name' => 'Park Punch List Visit', 'sku' => 'PK-PL-1', 'category' => 'service', 'unit_cost' => 90, 'unit_price' => 320, 'stock_qty' => 999],
            ['name' => 'Soft Floor Repair (room)', 'sku' => 'FL-RM-1', 'category' => 'service', 'unit_cost' => 280, 'unit_price' => 1450, 'stock_qty' => 999],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => 'each', 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Rosa Delgado', 'email' => 'rosa@example.com', 'phone' => '(623) 555-3301', 'company' => null, 'city' => 'Peoria', 'state' => 'AZ', 'zip' => '85345', 'address' => 'Space 42, Desert Palm Park'],
            ['name' => 'Ken Patel', 'email' => 'ken@westvalleyparks.com', 'phone' => '(623) 555-3310', 'company' => 'West Valley Parks', 'city' => 'Glendale', 'state' => 'AZ', 'zip' => '85301', 'address' => '5800 W Olive Ave'],
            ['name' => 'Nina Brooks', 'email' => 'ops@suncrestmh.com', 'phone' => '(623) 555-3399', 'company' => 'Suncrest MH', 'city' => 'Surprise', 'state' => 'AZ', 'zip' => '85374', 'address' => '15000 W Bell Rd'],
            ['name' => 'Omar Diaz', 'email' => 'omar.diaz@example.com', 'phone' => '(623) 555-3188', 'company' => null, 'city' => 'Peoria', 'state' => 'AZ', 'zip' => '85381', 'address' => 'Space 18, Canyon View'],
            ['name' => 'Lily Chen', 'email' => 'lily@oakandpine.org', 'phone' => '(623) 555-3177', 'company' => 'Oak & Pine Realty', 'city' => 'Glendale', 'state' => 'AZ', 'zip' => '85308', 'address' => '2020 N 67th Ave'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Roof coat — Desert Palm #42', 'service_type' => 'roof', 'status' => 'scheduled', 'sq_ft' => 900, 'quoted_amount' => 2800],
            ['customer' => 1, 'title' => 'Soft floor bath — West Valley Parks', 'service_type' => 'floor', 'status' => 'in_progress', 'sq_ft' => 80, 'quoted_amount' => 1650],
            ['customer' => 2, 'title' => 'Skirting replace — Suncrest row', 'service_type' => 'skirt', 'status' => 'quoted', 'sq_ft' => 120, 'quoted_amount' => 2100],
            ['customer' => 3, 'title' => 'Relevel — Canyon View #18', 'service_type' => 'level', 'status' => 'completed', 'sq_ft' => 0, 'quoted_amount' => 980, 'final_amount' => 950],
            ['customer' => 4, 'title' => 'Park punch list — listing prep', 'service_type' => 'inspect', 'status' => 'invoiced', 'sq_ft' => 0, 'quoted_amount' => 640, 'final_amount' => 620],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for Padline ERP.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * (8.10/100), 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * (1+8.10/100), 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * (1+8.10/100), 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Roof assess — Delgado'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'inspect',
                'starts_at' => now()->addDays(1)->setTime(8, 0),
                'ends_at' => now()->addDays(1)->setTime(10, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Floor repair — West Valley Parks'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'repair',
                'starts_at' => now()->addDays(2)->setTime(12, 0),
                'ends_at' => now()->addDays(2)->setTime(15, 0),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'Monsoon Leaks on Mobile Home Roofs',
                'excerpt' => 'Coatings fail when seams and penetrations are ignored.',
                'cover' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'body' => "Padline cleans, patches high-risk seams, and coats with UV-ready systems before summer storms hit West Valley parks.",
            ],
            [
                'title' => 'Why Soft Floors Need More Than a Rug',
                'excerpt' => 'Soft spots under baths usually mean wet subfloor — not just squeaks.',
                'cover' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
                'body' => "We cut back to sound deck, address moisture, and rebuild so residents walk safely again.",
            ],
            [
                'title' => 'Skirting That Passes Park Walk-Throughs',
                'excerpt' => 'Ventilation and access doors matter as much as looks.',
                'cover' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'body' => "Missing panels invite pests and failed inspections. Padline matches park standards and keeps crawl access usable.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Roof still drips after coat', 'body' => "1. Check flashings at vents.
2. Inspect seams near AC curb.
3. Confirm ponding areas.
4. Schedule a follow-up leak trace."],
            ['category' => 'issues', 'title' => 'Doors stick after monsoon', 'body' => "Settlement or moisture swell. Padline can relevel and reseal weatherstripping."],
            ['category' => 'guides', 'title' => 'Homeowner checklist before we arrive', 'body' => "Clear driveway space. Secure pets. Note park gate codes. Mark known leak spots."],
            ['category' => 'guides', 'title' => 'Roof coat day timeline', 'body' => "0–30 min: Clean and inspect.
30–90 min: Patch seams.
Final: Coat and photo log."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.
1. Add customers.
2. Create a repair ticket.
3. Schedule crews.
4. Track coats and panels.
5. Invoice and record payments.
6. Use AI Studio.
7. Update Settings."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.
Manager: jobs, inventory, invoices, scheduling.
Staff: field tickets and appointments."],
            ['category' => 'troubleshoot', 'title' => 'Coat inventory zero but truck has pails', 'body' => "Adjust stock_qty after count. Check open tickets holding materials."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
