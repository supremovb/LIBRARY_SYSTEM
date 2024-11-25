<?php

namespace App\Models;

use CodeIgniter\Model;

class Notification_model extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'message', 'type', 'status', 'created_at', 'email'];
    protected $useTimestamps = false;

    
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

    
    public function sendNotificationToUser($user_id, $message, $type)
    {
        
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($user_id);

        
        $data = [
            'user_id' => $user_id,
            'message' => $message,
            'type' => $type,
            'status' => 'UNREAD',
            'created_at' => date('Y-m-d H:i:s'), 
            'email' => $user['email'],  
        ];

        
        $this->insert($data);

        
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

            
            $notifications = [];
            foreach ($students as $student) {
                
                $notifications[] = [
                    'user_id' => $student->user_id,
                    'message' => $message,
                    'type' => $type,
                    'status' => 'UNREAD',
                    'created_at' => date('Y-m-d H:i:s'),
                    'email' => $student->email, 
                ];

                
                $email = \Config\Services::email();
                $email->setFrom('your_email@example.com', 'Library System');
                $email->setTo($student->email);
                $email->setSubject('New Arrival');
                $email->setMessage($message);
                $email->send();
            }

            
            $notificationBuilder = $db->table('notifications');
            if (!$notificationBuilder->insertBatch($notifications)) {
                $db->transRollback();
                log_message('error', 'Failed to insert notifications for all students.');
                return false;
            }

            
            $db->transCommit();
            log_message('info', 'Successfully sent notifications to all students.');
            return true;
        } else {
            log_message('info', 'No students found to send notifications.');
            return false;
        }
    }
}
