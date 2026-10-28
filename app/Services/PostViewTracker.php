<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class PostViewTracker
{
    public const COOKIE_NAME = 'visitor_id';
    public const COOKIE_MINUTES = 60 * 24 * 365; // 1 tahun

    protected array $botKeywords = [
        'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit',
        'whatsapp', 'telegram', 'curl', 'wget', 'python-requests',
        'headless', 'lighthouse', 'preview',
    ];

    public function track(Post $post, Request $request): void
    {
        if ($this->isBot($request)) {
            return;
        }

        $visitorId = $this->resolveVisitorId($request);

        try {
            $view = PostView::firstOrCreate(
                [
                    'post_id'    => $post->id,
                    'visitor_id' => $visitorId,
                ],
                [
                    'ip_hash'    => hash('sha256', $request->ip() . config('app.key')),
                    'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
                    'viewed_at'  => now(),
                ]
            );

            if ($view->wasRecentlyCreated) {
                $post->increment('views');
            }
        } catch (QueryException $e) {
            // Request bersamaan yang menabrak unique index: abaikan.
        }
    }

    public function hasRead(Post $post, Request $request): bool
    {
        $visitorId = $request->cookie(self::COOKIE_NAME);

        if (! $visitorId) {
            return false;
        }

        return PostView::where('post_id', $post->id)
            ->where('visitor_id', $visitorId)
            ->exists();
    }

    /**
     * Ambil ID pengunjung dari cookie, atau buat baru dan antrekan cookie-nya.
     */
    protected function resolveVisitorId(Request $request): string
    {
        $visitorId = $request->cookie(self::COOKIE_NAME);

        if (! $visitorId || ! Str::isUuid($visitorId)) {
            $visitorId = (string) Str::uuid();
        }

        // Perpanjang masa berlaku cookie setiap kunjungan
        Cookie::queue(self::COOKIE_NAME, $visitorId, self::COOKIE_MINUTES);

        return $visitorId;
    }

    protected function isBot(Request $request): bool
    {
        $agent = strtolower((string) $request->userAgent());

        if ($agent === '') {
            return true;
        }

        foreach ($this->botKeywords as $keyword) {
            if (str_contains($agent, $keyword)) {
                return true;
            }
        }

        return false;
    }
}