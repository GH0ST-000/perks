<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Partner;
use App\Models\PremiumOffer;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Users Statistics
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Categories Statistics
        $totalCategories = Category::count();
        $newCategoriesThisMonth = Category::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Premium Offers Statistics
        $totalOffers = PremiumOffer::count();
        $premiumOffers = PremiumOffer::where('is_premium', true)->count();
        $activeOffers = PremiumOffer::where('day_left', '>', 0)->count();
        $urgentOffers = PremiumOffer::where('day_left', '<=', 3)->where('day_left', '>', 0)->count();

        // Partners Statistics
        $totalPartners = Partner::count();
        $partnersWithCategories = Partner::has('categories')->count();
        $newPartnersThisMonth = Partner::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Blogs Statistics
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('is_published', true)->count();
        $draftBlogs = Blog::where('is_published', false)->count();
        $totalBlogViews = Blog::sum('views');

        return [
            // Users Stats
            Stat::make('Total Users', Number::format($totalUsers))
                ->description($newUsersToday . ' new today • ' . $newUsersThisMonth . ' this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart($this->getUserChartData()),
            
            // Categories Stats
            Stat::make('Categories', Number::format($totalCategories))
                ->description($newCategoriesThisMonth . ' added this month')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),
            
            // Premium Offers Stats
            Stat::make('Premium Offers', Number::format($totalOffers))
                ->description($premiumOffers . ' premium • ' . $urgentOffers . ' urgent')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning'),
            
            // Partners Stats
            Stat::make('Partners', Number::format($totalPartners))
                ->description($partnersWithCategories . ' with categories • ' . $newPartnersThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
            
            // Blogs Stats
            Stat::make('Blog Posts', Number::format($totalBlogs))
                ->description($publishedBlogs . ' published • ' . $draftBlogs . ' drafts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            
            // Active Offers
            Stat::make('Active Offers', Number::format($activeOffers))
                ->description('Offers with days remaining')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            
            // Blog Views
            Stat::make('Total Blog Views', Number::format($totalBlogViews))
                ->description('All-time views')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
        ];
    }

    protected function getUserChartData(): array
    {
        // Get user counts for the last 7 days
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = User::whereDate('created_at', $date)->count();
        }
        return $data;
    }
}
