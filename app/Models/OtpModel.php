<?php
namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $table      = 'user_otps';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'otp', 'otp_expiration', 'is_verified', 'created_at', 'token', 'token_expiration', 'type','email'];
}
