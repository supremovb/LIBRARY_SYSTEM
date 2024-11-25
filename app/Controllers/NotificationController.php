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

    
    public function sendNotificationToOverdue()
    {
        $overdueBooks = $this->transactionModel->getOverdueBooks(); 

        if (empty($overdueBooks)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No overdue books found.']);
        }

        $message = 'You have overdue books. Please return them as soon as possible.';
        $type = 'Overdue';

        foreach ($overdueBooks as $book) {
            
            $this->notificationModel->sendNotificationToUser($book['user_id'], $message, $type);
        }

        return $this->response->setJSON(['success' => true]);
    }


    
    public function index()
    {
        $user_id = session()->get('user_id');
        $data['notifications'] = $this->notificationModel->getNotifications($user_id);

        return view('student/notifications', $data);
    }

    
    public function markAsRead()
    {
        $user_id = session()->get('user_id');
        $this->notificationModel->updateStatusToRead($user_id);
        return $this->response->setJSON(['success' => true]);
    }

    public function cleanOldNotifications()
    {
        $user_id = session()->get('user_id');
        $this->notificationModel->removeOldNotifications($user_id);

        return $this->response->setJSON(['message' => 'Old notifications removed successfully.']);
    }

    
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




    
    public function showNotifications()
    {
        $user_id = session()->get('user_id');
        $notifications = $this->notificationModel->getNotifications($user_id);

        
        foreach ($notifications as $notification) {
            $this->notificationModel->markAsRead($notification['id']);
        }

        return view('student/notifications', ['notifications' => $notifications]);
    }
}
