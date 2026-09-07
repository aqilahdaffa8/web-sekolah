<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\DudiPartner;
use App\Models\Event;
use App\Models\Extracurricular;
use App\Models\Post;
use App\Models\Program;
use App\Models\Student;
use App\Models\TefaProduct;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * GET /api/public/home
     * Returns all data needed for the school's landing page.
     */
    public function index(): JsonResponse
    {
        $banners = Banner::orderBy('sort_order')->get()->map(fn (Banner $banner): array => [
            'id' => $banner->id,
            'image' => $banner->image_url,
            'link' => $banner->link_url,
            'title' => 'SMKN 1 Katapang',
            'subtitle' => 'Pendidikan vokasi yang membentuk talenta siap industri.',
        ]);

        $latestNews = Post::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'content' => $post->content,
                    'excerpt' => Str::limit(strip_tags($post->content), 120),
                    'thumbnail' => $post->image_url,
                    'image_url' => $post->image_url,
                    'category' => $post->category ? ['name' => $post->category->category_name] : ['name' => 'Umum'],
                    'published_at' => $post->created_at,
                ];
            });

        $upcomingEvents = Event::orderBy('event_date', 'asc')
            ->take(5)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date,
                    'start_date' => $event->event_date,
                    'location' => 'Kampus SMKN 1 Katapang',
                    'type' => 'Kegiatan Sekolah',
                ];
            });

        $featuredProducts = TefaProduct::with('program')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->product_name,
                    'description' => $p->description,
                    'price' => $p->price,
                    'stock' => $p->stock,
                    'image' => $p->image_url,
                    'category' => ['name' => $p->program->program_name ?? 'TeFA & Koperasi'],
                ];
            });

        $extracurriculars = Extracurricular::take(10)->get();

        $stats = [
            'students' => Student::count() ?: 1250,
            'teachers' => User::whereHas('roles', fn ($q) => $q->where('role_name', 'Guru'))->count() ?: 85,
            'programs' => Program::count() ?: 7,
            'partners' => DudiPartner::count() ?: 50,
        ];

        return response()->json([
            'banners' => $banners,
            'stats' => $stats,
            'latest_news' => $latestNews,
            'upcoming_events' => $upcomingEvents,
            'featured_products' => $featuredProducts,
            'extracurriculars' => $extracurriculars,
            'news' => $latestNews,
            'events' => $upcomingEvents,
            'products' => $featuredProducts,
        ]);
    }
}
