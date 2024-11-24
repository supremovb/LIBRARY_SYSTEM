<?php

namespace App\Models;

use CodeIgniter\Model;

class Notification_model extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'message', 'type', 'status', 'created_at', 'email'];
    protected $useTimestamps = false;

    // Get notifications for a specific user
    public function getNotifications($user_id)
    {
        return $this->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getUnreadCount($user_id)
    {
        return $this->where('user_id', $user_id)
            ->where('status', 'UNREAD')
            ->countAllResults();
    }


    public function updateStatusToRead($user_id)
    {
        return $this->set('status', 'READ')
            ->where('user_id', $user_id)
            ->where('status', 'UNREAD')
            ->update();
    }


    public function removeOldNotifications($user_id)
    {
        $yesterday = date('Y-m-d 00:00:00', strtotime('-1 day'));
        return $this->where('user_id', $user_id)
            ->where('created_at <', $yesterday)
            ->delete();
    }

    // Send notification to user and send an email
    public function sendNotificationToUser($user_id, $message, $type)
    {
        // Get user details to fetch email
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($user_id);

        // Prepare data for insertion
        $data = [
            'user_id' => $user_id,
            'message' => $message,
            'type' => $type,
            'status' => 'UNREAD',
            'created_at' => date('Y-m-d H:i:s'), // Manually set created_at
            'email' => $user['email'],  // Store email in the notifications table
        ];

        // Insert the data into the notifications table
        $this->insert($data);

        // Send an email to the user
        $email = \Config\Services::email();
        $email->setFrom('your_email@example.com', 'Library System');
        $email->setTo($user['email']);
        $email->setSubject('Overdue Books Notification');
        $email->setMessage($message);
        $email->send();
    }

    public function sendNotificationToAllStudents($message, $type)
    {
        $db = \Config\Database::connect();
        $userBuilder = $db->table('users');
        $userBuilder->where('role', 'student');
        $students = $userBuilder->get()->getResult();

        if (count($students) > 0) {
            $db->transBegin();

            // Prepare the data for batch insert into the notifications table
            $notifications = [];
            foreach ($students as $student) {
                // Add email to the notifications array
                $notifications[] = [
                    'user_id' => $student->user_id,
                    'message' => $message,
                    'type' => $type,
                    'status' => 'UNREAD',
                    'created_at' => date('Y-m-d H:i:s'),
                    'email' => $student->email, // Store email
                ];

                // Send email to each student
                $email = \Config\Services::email();
                $email->setFrom('your_email@example.com', 'Library System');
                $email->setTo($student->email);
                $email->setSubject('New Arrival');
                $email->setMessage($message);
                $email->send();
            }

            // Insert notifications into the notifications table
            $notificationBuilder = $db->table('notifications');
            if (!$notificationBuilder->insertBatch($notifications)) {
                $db->transRollback();
                log_message('error', 'Failed to insert notifications for all students.');
                return false;
            }

            // Commit the transaction if successful
            $db->transCommit();
            log_message('info', 'Successfully sent notifications to all students.');
            return true;
        } else {
            log_message('info', 'No students found to send notifications.');
            return false;
        }
    }
}
