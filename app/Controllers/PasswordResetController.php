<?php

namespace App\Controllers;

use App\Models\PasswordResetModel;
use CodeIgniter\Controller;

class PasswordResetController extends Controller
{
    public function cleanExpiredTokens()
    {
        $passwordResetModel = new PasswordResetModel();
        $currentTime = date('Y-m-d H:i:s');


        $passwordResetModel->where('expires_at <', $currentTime)->delete();

        echo "Expired tokens cleaned up successfully.";
    }
}
