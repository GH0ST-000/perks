<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerNotificationResource;
use App\Models\CustomerNotification;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerService $customerService
    ) {}

    /**
     * Send verification code to customer
     */
    public function sendVerificationCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_phone' => 'required|string|min:10|max:15',
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();

            // Managers must have a company
            if ($user->role === 'manager' && !$user->company_id) {
                return response()->json([
                    'message' => 'Manager must be associated with a company'
                ], 403);
            }

            $notification = $this->customerService->sendVerificationCode(
                customerPhone: $request->customer_phone,
                managerId: $user->id,
                companyId: $user->company_id,
                discount: $request->discount
            );

            return response()->json([
                'message' => 'Verification code sent successfully',
                'notification' => new CustomerNotificationResource($notification)
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send verification code'], 500);
        }
    }

    /**
     * Verify customer code
     */
    public function verifyCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_phone' => 'required|string',
            'verification_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $notification = $this->customerService->verifyCode(
            $request->customer_phone,
            $request->verification_code
        );

        if (!$notification) {
            return response()->json([
                'message' => 'Invalid or expired verification code'
            ], 404);
        }

        return response()->json([
            'message' => 'Code verified successfully',
            'notification' => new CustomerNotificationResource($notification)
        ]);
    }

    /**
     * Mark discount as used
     */
    public function markAsUsed(CustomerNotification $customerNotification): JsonResponse
    {
        $success = $this->customerService->markAsUsed($customerNotification->id);

        if (!$success) {
            return response()->json([
                'message' => 'Cannot mark notification as used'
            ], 400);
        }

        return response()->json([
            'message' => 'Discount marked as used successfully'
        ]);
    }

    /**
     * Get all notifications (with role-based filtering)
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Managers can only see their company's notifications
        if ($user->role === 'manager') {
            $notifications = $this->customerService->getNotificationsForManager($user);
        } else {
            // Admins can see all and apply filters
            $notifications = $this->customerService->getNotifications(
                companyId: $request->query('company_id'),
                managerId: $request->query('manager_id'),
                status: $request->query('status'),
                startDate: $request->query('start_date'),
                endDate: $request->query('end_date')
            );
        }

        return response()->json([
            'notifications' => CustomerNotificationResource::collection($notifications)
        ]);
    }

    /**
     * Get statistics
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Managers get stats for their company only
        if ($user->role === 'manager') {
            $stats = $this->customerService->getStatistics(
                companyId: $user->company_id,
                managerId: $user->id
            );
        } else {
            // Admins can filter by company
            $stats = $this->customerService->getStatistics(
                companyId: $request->query('company_id'),
                managerId: $request->query('manager_id')
            );

            // Also get stats by company for admin
            $stats['by_company'] = $this->customerService->getStatisticsByCompany();
        }

        return response()->json($stats);
    }

    /**
     * Get single notification details
     */
    public function getNotification(CustomerNotification $customerNotification): JsonResponse
    {
        $user = auth()->user();

        // Managers can only see their company's notifications
        if ($user->role === 'manager' && $customerNotification->company_id !== $user->company_id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'notification' => new CustomerNotificationResource($customerNotification->load(['company', 'manager']))
        ]);
    }
}
