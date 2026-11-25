<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Padline will follow up within one business day — sooner for active leaks.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'roof-coat-repair' => [
                'title' => 'Roof Coat & Leak Repair',
                'summary' => 'Elastomeric coats, flashings, and monsoon leak stops.',
                'body' => 'Padline cleans, patches, and coats mobile home roofs with systems suited to Arizona UV — then documents penetrations for the next storm season.',
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80',
            ],
            'soft-floor-repair' => [
                'title' => 'Soft Floor Repair',
                'summary' => 'Subfloor replacement where water and wear softened rooms.',
                'body' => 'Soft spots under baths and kitchens need careful deck repair. We cut back to sound substrate, treat moisture, and refinish for safe walking.',
                'image' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1600&q=80',
            ],
            'skirting-repair' => [
                'title' => 'Skirting Repair',
                'summary' => 'Ventilated skirting panels that keep pests out and airflow right.',
                'body' => 'Missing skirting invites animals and drafts. Padline matches panels, vents, and access doors so parks stay compliant.',
                'image' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1600&q=80',
            ],
            'leveling-blocks' => [
                'title' => 'Leveling & Support Blocks',
                'summary' => 'Relevel homes that settled on pads or piers.',
                'body' => 'Doors that stick and floors that slope often mean settlement. We relevel carefully and document support points.',
                'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=80',
            ],
            'weather-seal' => [
                'title' => 'Weather Seal Package',
                'summary' => 'Windows, doors, and seam sealing against dust and rain.',
                'body' => 'Desert dust and monsoon wind find every gap. We seal high-leak zones and advise on window upgrades when frames fail.',
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80',
            ],
            'park-inspection-prep' => [
                'title' => 'Park Inspection Prep',
                'summary' => 'Punch-list repairs before park or lender walk-throughs.',
                'body' => 'Padline runs a punch list against common park standards so sales and renewals are not delayed by fixable exterior items.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
