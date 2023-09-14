<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    {
        
    }

    public function getAll()
    {
        $notifications = $this->notificationService->getAll();
        return $this->successResponse(
            $this->resource($notifications, NotificationResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($notificationId)
    {
        $notification = $this->notificationService->find($notificationId);

        return $this->successResponse(
            $this->resource($notification, NotificationResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(NotificationRequest $request)
    {
        $validatedData = $request->validated();
        $notification = $this->notificationService->create($validatedData);

        return $this->successResponse(
            $this->resource($notification, NotificationResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(NotificationRequest $request, $notificationId)
    {
        $validatedData = $request->validated();
        $this->notificationService->update($validatedData, $notificationId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($notificationId)
    {
        $this->notificationService->delete($notificationId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
