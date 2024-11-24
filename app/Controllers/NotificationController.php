<?php

namespace App\Controllers;

use App\Models\Notification_model;
use App\Models\TransactionModel;

class NotificationController extends BaseController
{
    protected $notificationModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->notificationModel = new Notification_model();
        $this->transactionModel = new TransactionModel();
    }

    // Method to send notifications to overdue students
    public function sendNotificationToOverdue()
    {
        $overdueBooks = $this->transactionModel->getOverdueBooks(); // Assuming this method fetches overdue transactions

        if (empty($overdueBooks)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No overdue books found.']);
        }

        $message = 'You have overdue books. Please return them as soon as possible.';
        $type = 'Overdue';

        foreach ($overdueBooks as $book) {
            // Send notification and email
            $this->notificationModel->sendNotificationToUser($book['user_id'], $message, $type);
        }

        return $this->response->setJSON(['success' => true]);
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
    public function unreadCount()
    {
        $user_id = session()->get('user_id');
        $unreadCount = $this->notificationModel->getUnreadCount($user_id);
        return $this->response->setJSON(['unread_count' => $unreadCount]);
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
        $notifications = $this->notificationModel->getNotifications($user_id);
        $unread_count = $this->notificationModel->getUnreadCount($user_id);

        return $this->response->setJSON([
            'notifications' => $notifications,
            'unread_count' => $unread_count,
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
