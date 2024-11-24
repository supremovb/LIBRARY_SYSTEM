<?php

namespace App\Models;

use CodeIgniter\Model;

class Notification_model extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'message', 'type', 'status', 'created_at'];
    protected $useTimestamps = true;

    // Get notifications for a specific user
    public function getNotifications($user_id)
    {
        return $this->where('user_id', $user_id)->findAll();
    }

    // Update all notifications to 'READ' for a specific user
    public function updateStatusToRead($user_id)
    {
        return $this->set('status', 'READ')
            ->where('user_id', $user_id)
            ->where('status', 'UNREAD')
            ->update();
    }

    // Get count of unread notifications for a specific user
    public function getUnreadCount($user_id)
    {
        return $this->where('user_id', $user_id)
            ->where('status', 'UNREAD')
            ->countAllResults();
    }

    // Remove notifications older than yesterday for a specific user
    public function removeOldNotifications($user_id)
    {
        $yesterday = date('Y-m-d 00:00:00', strtotime('-1 day'));
        return $this->where('user_id', $user_id)
            ->where('created_at <', $yesterday)
            ->delete();
    }


    public function sendNotificationToAllStudents($message, $type)
    {
        // Fetch all student users
        $db = \Config\Database::connect();
        $userBuilder = $db->table('users');
        $userBuilder->where('role', 'student');
        $students = $userBuilder->get()->getResult();

        if (count($students) > 0) {
            $db->transBegin();

            // Prepare the data for batch insert into the notifications table
            $notifications = [];
            foreach ($students as $student) {
                $notifications[] = [
                    'user_id' => $student->user_id,
                    'message' => $message,
                    'type' => $type,
                    'status' => 'UNREAD',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }

            // Insert notifications into the `notifications` table
            $notificationBuilder = $db->table('notifications');
            if (!$notificationBuilder->insertBatch($notifications)) {
                // Rollback on failure
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
