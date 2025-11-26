<?php

namespace App\Services;

use App\Models\CustomerNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function __construct(
        private SmsService $smsService
    ) {}

    /**
     * Send verification code to customer
     */
    public function sendVerificationCode(
        string $customerPhone,
        int $managerId,
        int $companyId,
        float $discount
    ): CustomerNotification {
        // Validate phone number
        if (!$this->smsService->validatePhoneNumber($customerPhone)) {
            throw new \InvalidArgumentException('Invalid phone number format');
        }

        // Generate verification code
        $verificationCode = $this->smsService->generateVerificationCode();

        // Create notification record
        $notification = CustomerNotification::create([
            'customer_phone' => $customerPhone,
            'verification_code' => $verificationCode,
            'company_id' => $companyId,
            'manager_id' => $managerId,
            'discount' => $discount,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send SMS
        $this->smsService->sendVerificationCode($customerPhone, $verificationCode);

        return $notification;
    }

    /**
     * Verify customer code
     */
    public function verifyCode(string $customerPhone, string $verificationCode): ?CustomerNotification
    {
        $notification = CustomerNotification::where('customer_phone', $customerPhone)
            ->where('verification_code', $verificationCode)
            ->where('status', 'pending')
            ->first();

        if (!$notification) {
            return null;
        }

        // Check if expired
        if ($notification->isExpired()) {
            return null;
        }

        // Mark as verified
        $notification->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Send discount notification
        $this->smsService->sendDiscountNotification(
            $customerPhone,
            $notification->discount,
            $notification->company->name
        );

        return $notification;
    }

    /**
     * Mark discount as used
     */
    public function markAsUsed(int $notificationId): bool
    {
        $notification = CustomerNotification::find($notificationId);

        if (!$notification || $notification->status !== 'verified') {
            return false;
        }

        $notification->update([
            'status' => 'used',
            'usage_count' => $notification->usage_count + 1,
        ]);

        return true;
    }

    /**
     * Get all notifications with filtering
     */
    public function getNotifications(
        ?int $companyId = null,
        ?int $managerId = null,
        ?string $status = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): Collection {
        $query = CustomerNotification::with(['company', 'manager'])
            ->orderBy('created_at', 'desc');

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($managerId) {
            $query->where('manager_id', $managerId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->get();
    }

    /**
     * Get notifications for a manager (filtered by their company)
     */
    public function getNotificationsForManager(User $manager): Collection
    {
        if (!$manager->company_id) {
            throw new \Exception('Manager is not associated with any company');
        }

        return $this->getNotifications(
            companyId: $manager->company_id,
            managerId: $manager->id
        );
    }

    /**
     * Get statistics for admin dashboard
     */
    public function getStatistics(?int $companyId = null, ?int $managerId = null): array
    {
        $query = CustomerNotification::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($managerId) {
            $query->where('manager_id', $managerId);
        }

        return [
            'total_notifications' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'verified' => (clone $query)->where('status', 'verified')->count(),
            'used' => (clone $query)->where('status', 'used')->count(),
            'total_discount_given' => (clone $query)
                ->where('status', 'used')
                ->sum('discount'),
            'total_usage_count' => (clone $query)->sum('usage_count'),
        ];
    }

    /**
     * Get statistics grouped by company
     */
    public function getStatisticsByCompany(): array
    {
        return CustomerNotification::select(
            'company_id',
            DB::raw('COUNT(*) as total_notifications'),
            DB::raw('SUM(CASE WHEN status = "used" THEN 1 ELSE 0 END) as used_count'),
            DB::raw('SUM(CASE WHEN status = "used" THEN discount ELSE 0 END) as total_discount'),
            DB::raw('SUM(usage_count) as total_usage')
        )
            ->with('company')
            ->groupBy('company_id')
            ->get()
            ->toArray();
    }
}
