<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function forgotPassword()
    {
        return view('forgot_password');
    }

    public function sendResetLink()
{
    $email = $this->request->getPost('email');
    $userModel = new UserModel();

    $user = $userModel->where('email', $email)->first();

    if ($user) {
        // Generate reset token and expiry
        $resetToken = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $userModel->update($user['id'], [
            'reset_token' => $resetToken,
            'reset_token_expiry' => $expiry
        ]);

        // Send email (configure email settings in app/Config/Email.php)
        $email = \Config\Services::email();
        $email->setFrom('your_email@example.com', 'Your App Name');
        $email->setTo($user['email']);
        $email->setSubject('Password Reset Request');
        $email->setMessage(
            'Click the link to reset your password: ' .
            base_url("reset-password/$resetToken")
        );
        $email->send();

        // Flash success message
        return redirect()->to('/forgot-password')->with('success', 'A password reset link has been sent to your registered email.');
    } else {
        // Flash error message if email is not found
        return redirect()->to('/forgot-password')->with('error', 'Email not found.');
    }
}


    public function resetPassword($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)
            ->where('reset_token_expiry >=', date('Y-m-d H:i:s'))
            ->first();

        if ($user) {
            return view('reset_password', ['token' => $token]);
        }

        return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset link.');
    }

    public function updatePassword()
{
    $token = $this->request->getPost('token');
    $password = $this->request->getPost('password');
    $userModel = new UserModel();

    $user = $userModel->where('reset_token', $token)->first();

    if ($user) {
        // Update password and reset token
        $userModel->update($user['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expiry' => null
        ]);

        // Set success flash data and redirect to reset-password with the token
        session()->setFlashdata('success', 'Password successfully updated.');
        return redirect()->to('/reset-password/' . $token);
    }

    // Set error flash data and redirect to forgot-password page
    session()->setFlashdata('error', 'Failed to reset password.');
    return redirect()->to('/forgot-password');
}
}