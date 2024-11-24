<?php

namespace App\Controllers;

use App\Models\Notification_model;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new Notification_model();
    }



    // Method to show the notifications page
    public function index()
    {
        $user_id = session()->get('user_id');
        $data['notifications'] = $this->notificationModel->getNotifications($user_id);

        return view('student/notifications', $data);
    }

    // Method to update the status of the notifications to 'READ'
    public function markAsRead()
    {
        $user_id = session()->get('user_id');
        $this->notificationModel->updateStatusToRead($user_id);

        return redirect()->to('/student/notifications');
    }

    // Method to get the unread notifications count for the navbar
    public function getUnreadCount()
    {
        $user_id = session()->get('user_id');
        $unread_count = $this->notificationModel->getUnreadCount($user_id);
        return $this->response->setJSON(['unread_count' => $unread_count]);
    }

    public function fetchNotifications()
    {
        $user_id = session()->get('user_id');
        $notifications = $this->notificationModel->getNotifications($user_id);

        return $this->response->setJSON($notifications);
    }

    // Method to fetch, update, and clean notifications
    public function updateNotifications()
    {
        $user_id = session()->get('user_id');

        // Mark all unread notifications as read
        $this->notificationModel->updateStatusToRead($user_id);

        // Remove notifications from previous days
        $this->notificationModel->removeOldNotifications($user_id);

        // Fetch remaining notifications and unread count
        $notifications = $this->notificationModel->getNotifications($user_id);
        $unread_count = $this->notificationModel->getUnreadCount($user_id);

        return $this->response->setJSON([
            'notifications' => $notifications,
            'unread_count' => $unread_count
        ]);
    }


    // Fetch and display all notifications
    public function showNotifications()
    {
        $user_id = session()->get('user_id');
        $notifications = $this->notificationModel->getNotifications($user_id);

        // Mark all notifications as read
        foreach ($notifications as $notification) {
            $this->notificationModel->markAsRead($notification['id']);
        }

        return view('student/notifications', ['notifications' => $notifications]);
    }
}
