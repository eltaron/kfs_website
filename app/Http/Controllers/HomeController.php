<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityGuideCategory;
use App\Models\HeroSlider;
use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\Landmark;
use App\Models\Location;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Statistic;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Settings (very important)
        $settings = Setting::pluck('value', 'key');

        // 2. Hero Slider
        $heroSlides = HeroSlider::where('is_active', true)->orderBy('order')->get();

        // 3. Events Section (assuming events are posts from a specific category)
        $events = Post::whereHas('category', fn($q) => $q->where('slug', 'events'))
            ->where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        // 4. Statistics
        $statistics = Statistic::orderBy('order')->take(3)->get();

        // 5. News Section
        $featuredNews = Post::where('is_featured', true)
            ->where('is_published', true)
            ->whereHas('category', fn($q) => $q->where('slug', '!=', 'events')) //  <--  التعديل هنا
            ->latest('published_at')
            ->first();

        // Get the latest news, but EXCLUDE events
        $latestNews = Post::where('is_featured', false)
            ->where('is_published', true)
            ->whereHas('category', fn($q) => $q->where('slug', '!=', 'events')) //  <--  التعديل هنا
            ->latest('published_at')
            ->take(3)
            ->get();
        // 6. Services
        $services = Service::whereNull('parent_id')
            ->orderBy('created_at', 'desc')->take(3)
            ->get();

        // 7. Investments
        $investments = Investment::orderBy('order')->take(2)->get();

        // 8. Landmarks (Tourism)
        $landmarks = Landmark::orderBy('order')->get();

        // 9. Projects
        $projects = Project::where('category', '!=', 'استثماري')->orderBy('is_highlighted', 'desc')->take(3)->get(); // Show highlighted first

        // 10. Achievements (assuming they are posts from another category)
        // You might need a separate model if they are more complex
        $achievements = Post::whereHas('category', fn($q) => $q->where('slug', 'achievements'))
            ->latest()->take(3)->get();
        $mainAchievementVideo = Setting::where('key', 'achievements_video_url')->first(); // Assuming you add this setting

        // 11. Partners (Apps)
        $partners = Partner::orderBy('created_at', 'asc')->get();

        // 12. City Guide
        $guideCategories = CityGuideCategory::withCount('locations')->get();
        $allLocations = Location::with('cityGuideCategory')->get();

        $nationalProjects = Project::whereIn('category', ['قومي', 'حياة كريمة'])->take(3)->get();
        $investmentProjects = Project::where('category', 'استثماري')->take(3)->get();

        // Fetch Investment Plans
        $investmentPlans = InvestmentPlan::orderBy('year_range', 'desc')->take(6)->get();

        return view('home.index', compact(
            'settings',
            'heroSlides',
            'events',
            'statistics',
            'featuredNews',
            'latestNews',
            'services',
            'investments',
            'landmarks',
            'projects',
            'achievements',
            'mainAchievementVideo',
            'partners',
            'guideCategories',
            'allLocations',
            'nationalProjects',
            'investmentProjects',
            'investmentPlans'
        ));
    }
    public function wait()
    {
        return view('home.wait');
    }
}
