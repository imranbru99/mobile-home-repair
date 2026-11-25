<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'roof leak coat monsoon price',
            'a' => 'Roof coat and leak repairs are scoped by size and condition. Padline provides written estimates after inspection.',
        ],
        [
            'q' => 'soft floor bath kitchen repair',
            'a' => 'We replace soft subfloor sections, treat moisture, and refinish for safe walking.',
        ],
        [
            'q' => 'skirting missing panels park',
            'a' => 'We install ventilated skirting that meets common park standards and keeps crawl access.',
        ],
        [
            'q' => 'leveling doors stick settle',
            'a' => 'Sticking doors often mean settlement. Padline relevels and documents support points.',
        ],
        [
            'q' => 'schedule appointment peoria glendale',
            'a' => 'Mon–Sat crews across Peoria, Glendale, Surprise, and nearby West Valley parks.',
        ],
        [
            'q' => 'park manager bulk repairs',
            'a' => 'We work with park managers on punch lists and multi-unit schedules via the ERP.',
        ],
        [
            'q' => 'emergency leak call',
            'a' => 'Leak calls are prioritized — call (623) 555-0167 and describe the space number.',
        ],
        [
            'q' => 'weather seal windows dust',
            'a' => 'Weather seal packages close dust and rain gaps at windows, doors, and seams.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a fire damage restoration company about: {$prompt}",
            'description' => "Write a compelling service description for fire and smoke restoration: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a fire damage restoration SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this fire restoration operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Padline Mobile Repair. We repair roofs, floors, and skirting for West Valley mobile homes. Ask about leaks, soft floors, or booking — or call (623) 555-0167.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Padline Mobile Repair assistant for a Peoria, AZ mobile home repair company. Be concise and practical. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are the Padline Mobile Repair assistant for a Peoria, AZ mobile home repair company. Be concise and practical. Never invent illegal claims.
{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
