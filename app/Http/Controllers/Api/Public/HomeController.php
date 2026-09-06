<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Post;
use App\Models\TefaProduct;
use App\Models\Extracurricular;
use App\Models\Student;
use App\Models\User;
use App\Models\Program;
use App\Models\DudiPartner;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * GET /api/public/home
     * Returns all data needed for the school's landing page.
     */
    public function index(): JsonResponse
    {
        $banners = Banner::orderBy('sort_order')->get();

        $latestNews = Post::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($post) {
                return [
                    'id'           => $post->id,
                    'title'        => $post->title,
                    'slug'         => $post->slug,
                    'content'      => $post->content,
                    'excerpt'      => \Illuminate\Support\Str::limit(strip_tags($post->content), 120),
                    'thumbnail'    => $post->image_url,
                    'image_url'    => $post->image_url,
                    'category'     => $post->category ? ['name' => $post->category->category_name] : ['name' => 'Umum'],
                    'published_at' => $post->created_at,
                ];
            });

        $upcomingEvents = Event::orderBy('event_date', 'asc')
            ->take(5)
            ->get()
            ->map(function ($event) {
                return [
                    'id'          => $event->id,
                    'title'       => $event->title,
                    'description' => $event->description,
                    'event_date'  => $event->event_date,
                    'start_date'  => $event->event_date,
                    'location'    => 'Kampus SMKN 1 Katapang',
                    'type'        => 'Kegiatan Sekolah',
                ];
            });

        $featuredProducts = TefaProduct::with('program')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id'          => $p->id,
                    'name'        => $p->product_name,
                    'description' => $p->description,
                    'price'       => $p->price,
                    'stock'       => $p->stock,
                    'image'       => $p->image_url,
                    'category'    => ['name' => $p->program->program_name ?? 'TeFA & Koperasi'],
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
<<<<<<< HEAD
            'banners'           => $banners,
            'stats'             => $stats,
            'latest_news'       => $latestNews,
            'upcoming_events'   => $upcomingEvents,
            'featured_products' => $featuredProducts,
            'extracurriculars'  => $extracurriculars,
=======
            'banners' => Banner::where('is_active', true)->orderBy('order')->get(),
            'news' => Post::with('category')
                ->where('status', 'published')
                ->latest('published_at')
                ->take(5)
                ->get(),
            'events' => Event::where('status', 'published')
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->take(5)
                ->get(),
            'products' => TefaProduct::where('is_featured', true)->take(6)->get(),
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
        ]);
    }
}
