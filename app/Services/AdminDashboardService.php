<?php

namespace App\Services;

use App\Models\User;

class AdminDashboardService
{
    public function __construct(
        private UserService $userService
    ) {}

    public function getDashboardStats(): array
    {
        return [
            'users' => $this->userService->getTotalUsersCount(),
            'products' => 0, // TODO: Replace with actual product count when implemented
            'orders' => 0,   // TODO: Replace with actual order count when implemented
            'revenue' => 0,  // TODO: Replace with actual revenue when implemented
        ];
    }

    public function getRecentActivities(): array
    {
        // TODO: Replace with actual activity tracking from database
        // This could query an activities table or aggregate from various models
        return [
            [
                'title' => 'New user registered',
                'status' => 'Completed',
                'user' => User::latest()->first()?->name ?? 'Unknown',
                'date' => now()->format('M d, Y')
            ],
            [
                'title' => 'System update',
                'status' => 'Completed',
                'user' => 'Admin',
                'date' => now()->subDay()->format('M d, Y')
            ]
        ];
    }

    public function getDashboardData(): array
    {
        return [
            'stats' => $this->getDashboardStats(),
            'recentActivities' => $this->getRecentActivities(),
        ];
    }
}
