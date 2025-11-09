<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\NotificationResource;

class NotificationsController extends Controller
{
   public function index()
    {
        try {
            $notifications = Notifications::with('user')->get();
            return $this->successResponse(
                NotificationResource::collection($notifications),
                'Notifications retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve notifications: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'message' => 'required|string',
                'status' => 'sometimes|in:read,unread',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $notification = Notifications::create($validator->validated());
            $notification->load('user');

            return $this->successResponse(
                new NotificationResource($notification),
                'Notification created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create notification: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $notification = Notifications::with('user')->findOrFail($id);
            return $this->successResponse(
                new NotificationResource($notification),
                'Notification retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Notification not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $notification = Notifications::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'message' => 'sometimes|string',
                'status' => 'sometimes|in:read,unread',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $notification->update($validator->validated());
            $notification->load('user');

            return $this->successResponse(
                new NotificationResource($notification),
                'Notification updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update notification: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $notification = Notifications::findOrFail($id);
            $notification->delete();
            return $this->successResponse(null, 'Notification deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete notification: ' . $e->getMessage(), 500);
        }
    }
}
