<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\PasswordResetModel;
use App\Models\TransactionModel;
use App\Models\OtpModel;
use CodeIgniter\Controller;

class UserController extends BaseController
{

    protected $transactionModel;
    protected $userModel;
    protected $passwordResetModel;
    protected $email;
    

    public function __construct()
    {
        

        $this->transactionModel = new TransactionModel(); // Initialize the model
        $this->userModel = new UserModel();
        $this->passwordResetModel = new PasswordResetModel();
        $this->email = \Config\Services::email();
    }


public function get_recommendations($book_id)
{

    $bookModel = new \App\Models\BookModel();
    $book = $bookModel->find($book_id);
    
    if (!$book) {
        return json_encode(['status' => 'error', 'message' => 'Book not found']);
    }


    $category_id = $book['category_id'];
    $recommendedBooks = $bookModel->where('category_id', $category_id)->findAll();

    return json_encode(['status' => 'success', 'books' => $recommendedBooks]);
}

    

    private function sendVerificationEmail($email, $otp, $token)
{
    $emailService = \Config\Services::email();

    $verificationLink = base_url('/verify-email?token=' . $token);

    $message = "
        <p>Your OTP is: <strong>{$otp}</strong>. It is valid for 10 minutes.</p>
        <p>Alternatively, you can click the link below to verify your account:</p>
        <p><a href='{$verificationLink}'>Verify My Account</a></p>
        <p>If you did not register for this account, please ignore this email.</p>
    ";

    $emailService->setFrom('no-reply@yourdomain.com', 'Library System');
    $emailService->setTo($email);
    $emailService->setSubject('Your Verification Code');
    $emailService->setMessage($message);

    return $emailService->send();
}


public function verifyEmail()
{
    $token = $this->request->getGet('token'); // Get token from URL
    $otpModel = new \App\Models\OtpModel();


    $currentDateTime = date('Y-m-d H:i:s');
    $otpRecord = $otpModel
        ->where('token', $token)
        ->where('token_expiration >', $currentDateTime) // Token not expired
        ->first();

    if (!$otpRecord) {

        return redirect()->to('/register')->with('msg', 'Invalid or expired token.');
    }


    $otpModel->update($otpRecord['id'], ['is_verified' => 1]);


    return redirect()->to('/login')->with('email_verification_success', 'Your email has been successfully verified!');
}



public function verifyOtp()
{
    $session = session();
    log_message('debug', 'Session user_id at verifyOtp: ' . $session->get('user_id'));
    return view('verify_otp'); // Display the OTP verification form
}



public function processOtp()
{
    $session = session();
    $otpModel = new \App\Models\OtpModel();
    $userModel = new \App\Models\UserModel();  // User model to delete the user if OTP fails


    $currentDateTime = date('Y-m-d H:i:s'); // Current date and time in 'Y-m-d H:i:s' format

    $userId = $session->get('user_id'); // Retrieve user ID from session
    log_message('debug', 'Session User ID: ' . $userId);

    if (!$userId) {
        log_message('debug', 'No User ID found in session.');
        $session->setFlashdata('msg', 'Session expired or invalid. Please log in again.');
        return redirect()->to('/login');
    }


    $otpRecord = $otpModel
        ->where('user_id', $userId)
        ->where('otp_expiration >', $currentDateTime) // Compare the full datetime (date and time)
        ->first();


    if (!$otpRecord) {
        log_message('debug', 'OTP expired or invalid for user ID: ' . $userId);


        $userModel->delete($userId); // Deletes the user


        $otpModel->where('user_id', $userId)->delete();

        $session->setFlashdata('msg', 'OTP expired or invalid. The user has been deleted.');
        return redirect()->to('/register');  // Redirect to registration or login page
    }


    $storedOtp = trim((string) $otpRecord['otp']);
    log_message('debug', 'Stored OTP: ' . $storedOtp);


    $otp = trim((string) $this->request->getPost('otp'));
    if ($otp !== $storedOtp) {
        log_message('debug', 'OTP mismatch. Input: "' . $otp . '", Stored: "' . $storedOtp . '"');
        $session->setFlashdata('msg', 'Invalid OTP. Please try again.');
        return redirect()->to('/verify-otp');
    }


    if ((int)$otpRecord['is_verified'] === 1) {
        log_message('debug', 'OTP already verified for user ID: ' . $userId);
        $session->setFlashdata('msg', 'This OTP has already been verified.');
        return redirect()->to('/verify-otp');
    }


    $otpModel->update($otpRecord['id'], ['is_verified' => 1]);
    log_message('debug', 'OTP verified successfully for user ID: ' . $userId);

    $session->setFlashdata('registration_success', 'You have successfully registered! Please login.');
    return redirect()->to('/login');
}




    /**
     * Show the Forgot Password form
     */
    public function forgotPassword()
    {
        helper(['form']);
        echo view('auth/forgot_password');
    }

    /**
     * Handle Forgot Password form submission
     */
    public function sendResetLink()
{
    helper(['form', 'session']);  // Ensure session helper is loaded

    $rules = [
        'email' => 'required|valid_email'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $email = $this->request->getVar('email');
    $user = $this->userModel->where('email', $email)->first();

    if (!$user) {

        return redirect()->back()->with('success', 'If that email address exists in our system, we have sent a password reset link to it.');
    }

    try {

        $token = bin2hex(random_bytes(50));


        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));


        $this->passwordResetModel->insert([
            'user_id' => $user['user_id'],
            'email' => $email,
            'token' => $token,
            'expires_at' => $expires_at
        ]);


        $resetLink = base_url("reset-password?token={$token}");


        $data = [
            'firstname' => $user['firstname'], // Passing only the firstname
            'resetLink' => $resetLink
        ];


        $message = view('emails/password_reset', $data);


        $this->email->setFrom('no-reply@yourdomain.com', 'Library System');
        $this->email->setTo($email);
        $this->email->setSubject('Password Reset Request');
        $this->email->setMessage($message);

        if ($this->email->send()) {
            log_message('debug', 'Password reset link sent to ' . $email);
            return redirect()->back()->with('success', 'If that email address exists in our system, we have sent a password reset link to it.');
        } else {
            log_message('error', 'Password reset email failed to send to ' . $email);
            return redirect()->back()->with('error', 'Failed to send password reset email. Please try again.');
        }
    } catch (\Exception $e) {
        log_message('error', 'Error occurred: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
    }    
}

    /**
     * Show the Reset Password form
     */
    public function resetPassword()
{
    helper(['form']);
    $token = $this->request->getGet('token');

    if (!$token) {
        return redirect()->to('/login')->with('error', 'Invalid password reset token.');
    }

    $resetRequest = $this->passwordResetModel->where('token', $token)->first();

    if (!$resetRequest) {
        return redirect()->to('/login')->with('error', 'Invalid password reset token.');
    }


    if (strtotime($resetRequest['expires_at']) < time()) {
        $this->passwordResetModel->delete($resetRequest['id']); // Remove expired token
        return redirect()->to('/login')->with('error', 'The password reset link has expired. Please request a new one.');
    }


    $data['token'] = $token;
    echo view('auth/reset_password', $data);
}


    /**
     * Handle Reset Password form submission
     */
    public function updatePassword()
    {
        helper(['form']);
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]|matches[pass_confirm]',
            'pass_confirm' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getVar('token');
        $password = $this->request->getVar('password');

        $resetRequest = $this->passwordResetModel->where('token', $token)->first();

        if (!$resetRequest) {
            return redirect()->to('/login')->with('error', 'Invalid password reset token.');
        }

        if (strtotime($resetRequest['expires_at']) < time()) {

            $this->passwordResetModel->delete($resetRequest['id']);
            return redirect()->to('/login')->with('error', 'Password reset token has expired.');
        }


        $user = $this->userModel->find($resetRequest['user_id']);
        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found.');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->userModel->update($user['user_id'], ['password' => $hashedPassword]);


        $this->passwordResetModel->delete($resetRequest['id']);

        return redirect()->to('/login')->with('password_reset_success', 'Your password has been reset successfully. Please log in.');
    }

    
    public function login()
{
    $session = session();


    if ($session->get('logged_in')) {

        $currentTime = time();
        $lastActivity = $session->get('last_activity') ?? $currentTime;

        if (($currentTime - $lastActivity) > 1800) { // 30 minutes timeout
            $session->destroy(); // Destroy the session
            $session->setFlashdata('msg', 'Your session has expired. Please log in again.');
            return redirect()->to('/login');
        }


        $session->set('last_activity', $currentTime);


        if ($session->get('role') === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/user/dashboard');
        }
    }

    helper(['form']);
    echo view('login');
}




public function viewProfile()
{
    $session = session();


    if (!$session->get('logged_in') || $session->get('role') != 'student') {
        return redirect()->to('/');
    }

    $user_id = $session->get('user_id');
    $userModel = new UserModel();
    $otpModel = new OtpModel();  // Ensure you have an OTP model for interacting with the user_otps table


    $data['user'] = $userModel->find($user_id);

    if (!$data['user']) {
        return redirect()->to('/')->with('error', 'User not found.');
    }


    $emailVerification = $otpModel->where('user_id', $user_id)
                                   ->where('email', $data['user']['email'])
                                   ->first();


    if ($emailVerification && $emailVerification['is_verified'] == 1) {
        $data['emailVerified'] = true;  // Email is verified
    } else {
        $data['emailVerified'] = false; // Email is not verified
    }


    return view('student/view_profile', $data);
}


    public function updateProfile()
{
    $session = session();
    $userModel = new UserModel();
    $otpModel = new OtpModel(); // Make sure you have an OTP model for interacting with the user_otps table

    if (!$session->get('logged_in') || $session->get('role') != 'student') {
        return redirect()->to('/');
    }

    $user_id = $session->get('user_id');
    $data = $this->request->getPost();


    $currentUser = $userModel->find($user_id);
    if (!$currentUser) {
        log_message('error', 'User not found: ' . $user_id);
        return redirect()->to('student/view-profile')->with('error', 'User not found.');
    }


    $emailChanged = isset($data['email']) && $currentUser['email'] !== $data['email'];


        if (!empty($data['new_password']) && $data['new_password'] !== $data['confirm_password']) {

            return redirect()->to('student/view-profile')->with('error', 'Passwords do not match!');
        }



    if (!$emailChanged) {
        unset($data['email']); // Don't update the email if it's not changed
    }


    if (isset($data['username']) && $currentUser['username'] === $data['username']) {
        unset($data['username']); // Don't update username if it's not changed
    }


    $validation = \Config\Services::validation();


    $validationRules = [
        'firstname' => 'required|min_length[3]|max_length[100]',
        'lastname'  => 'required|min_length[3]|max_length[100]',
        'username'  => 'required|min_length[3]|max_length[50]',
        'email'     => 'permit_empty|valid_email|max_length[100]',  // Set as permit_empty so we can skip it when not changed
        'new_password' => 'permit_empty|min_length[6]|max_length[255]',
        'confirm_password' => 'permit_empty|matches[new_password]',
        'course' => 'required|min_length[3]|max_length[100]',
        'year' => 'required|min_length[1]|max_length[10]',
    ];

    if (!isset($data['username']) || $currentUser['username'] === $data['username']) {
        $validationRules['username'] = 'permit_empty'; // Allow empty value for username if unchanged
    }

    $validation->setRules($validationRules);


    if ($emailChanged) {
        $existingUser = $userModel->where('email', $data['email'])->where('user_id !=', $user_id)->first();
        if ($existingUser) {
            return redirect()->to('student/view-profile')->with('error', 'This email address is already in use.');
        }
    }

    if (!$validation->run($data)) {
        log_message('error', 'Validation Errors: ' . print_r($validation->getErrors(), true));
        return redirect()->to('student/view-profile')->with('error', 'Validation failed.');
    }


    $photo = $this->request->getFile('photo');
    if ($photo && $photo->isValid()) {
        $photoName = $photo->getRandomName();
        $uploadPath = ROOTPATH . 'uploads/user_photos';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $photo->move($uploadPath, $photoName);


        $data['photo'] = base_url('uploads/user_photos/' . $photoName);
    } else {
        unset($data['photo']);
    }


    if (!empty($data['new_password']) && $data['new_password'] === $data['confirm_password']) {
        $data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
    } else {
        unset($data['password']); // Remove password if no change
    }

    unset($data['new_password'], $data['confirm_password'], $data['role']);
    $data['updated_at'] = date('Y-m-d H:i:s');


    try {
        if (!$userModel->update($user_id, $data)) {
            $errors = implode(', ', $userModel->errors());
            throw new \Exception($errors);
        }


        if (isset($data['firstname'])) {
            $session->set('firstname', $data['firstname']);
        }


        if ($emailChanged) {

            $verificationToken = bin2hex(random_bytes(32)); // Generate a random token
            $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid for 1 hour


            $otpData = [
                'user_id' => $user_id,
                'email' => $data['email'],
                'token' => $verificationToken,
                'token_expiration' => $tokenExpiration,
                'type' => 'email_verification' // Optionally add a type field to distinguish OTP types
            ];

            if (!$otpModel->save($otpData)) {
                throw new \Exception('Failed to save OTP in the database.');
            }


            $verificationLink = base_url("verify-email?token=$verificationToken");

            $email = \Config\Services::email();
            $email->setTo($data['email']);
            $email->setSubject('Verify Your Email');
            $email->setMessage("Click the link below to verify your email:\n\n" . $verificationLink);

            if (!$email->send()) {
                log_message('error', 'Email sending failed: ' . $email->printDebugger());
                return redirect()->to('student/view-profile')->with('error', 'Failed to send verification email.');
            }
        }

        return redirect()->to('student/view-profile')->with('success', 'Profile updated successfully. A verification link has been sent to your email.');
    } catch (\Exception $e) {
        log_message('error', 'Update Error: ' . $e->getMessage());
        return redirect()->to('student/view-profile')->with('error', 'An error occurred while updating your profile.');
    }
}

    

public function authenticate()
{
    $session = session();
    $model = new UserModel();


    $input = trim($this->request->getVar('username')); // Can be student_id or username
    $password = trim($this->request->getVar('password'));

    if (empty($input) || empty($password)) {
        $session->setFlashdata('msg', 'Student ID/Username and Password are required.');
        return redirect()->to('/login');
    }


    $data = $model->where('username', $input)
                  ->orWhere('student_id', $input)
                  ->first();

    if ($data) {
        $hashedPassword = $data['password'];


        log_message('debug', 'Password hash from DB for user ' . $input . ': ' . $hashedPassword);


        if (password_verify($password, $hashedPassword)) {

            if (
                ($input === $data['username'] && $input !== $data['username']) ||
                ($input === $data['student_id'] && strcmp($input, $data['student_id']) !== 0)
            ) {
                log_message('error', 'Case mismatch in Student ID/Username: ' . $input);
                $session->setFlashdata('msg', 'Student ID/Username is incorrect (case-sensitive).');
                return redirect()->to('/login');
            }


            $ses_data = [
                'user_id' => $data['user_id'],
                'username' => $data['username'],
                'student_id' => $data['student_id'],
                'role' => $data['role'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'logged_in' => TRUE
            ];
            $session->set($ses_data);


            return $data['role'] === 'admin'
                ? redirect()->to('/admin/dashboard')
                : redirect()->to('/user/dashboard');
        } else {
            log_message('error', 'Password mismatch for input: ' . $input);
            $session->setFlashdata('msg', 'Password is incorrect.');
            return redirect()->to('/login');
        }
    } else {
        log_message('error', 'Student ID/Username not found: ' . $input);
        $session->setFlashdata('msg', 'Student ID/Username not found.');
        return redirect()->to('/login');
    }
}


    public function dashboard()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        if ($session->get('role') == 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {

            $bookModel = new BookModel();
            $data['books'] = $bookModel->where('status', 'available')->findAll();

            echo view('student/dashboard', $data);
        }
    }

    public function myBorrowedBooks()
{
    $session = session();
    if (!$session->get('logged_in') || $session->get('role') != 'student') {
        return redirect()->to('/');
    }

    $user_id = $session->get('user_id');
    

    $borrowedBooks = $this->transactionModel
        ->select('transactions.transaction_id, transactions.borrow_date, transactions.due_date, books.title, books.author, books.isbn, books.published_date, books.photo')
        ->join('books', 'books.book_id = transactions.book_id')
        ->where('transactions.user_id', $user_id)
        ->where('transactions.status', 'borrowed')
        ->findAll();

    $data['borrowed'] = $borrowedBooks;


    echo view('student/my_borrowed_books', $data);
}


    

    public function register()
    {
        helper(['form']);
        echo view('student/registration');
    }

    public function createStudent()
{
    $session = session();
    $model = new UserModel();
    $otpModel = new \App\Models\OtpModel();


    $validation = \Config\Services::validation();
    
    $validation->setRules([
        'email' => 'required|valid_email|is_unique[users.email]',
        'username' => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username]',
        'password' => 'required|min_length[6]',
        'confirm_password' => 'required|matches[password]', // Ensure confirm password matches password
        'firstname' => 'required|alpha_space|min_length[2]|max_length[50]',
        'lastname' => 'required|alpha_space|min_length[2]|max_length[50]',
        'course' => 'required|alpha_space|min_length[2]|max_length[50]',
        'year' => 'required|alpha_numeric_space|min_length[1]|max_length[20]',
    ]);

    if ($this->validate('student_registration')) {

        $existingUser = $model->where('username', $this->request->getPost('username'))->first();
        if ($existingUser) {
            $session->setFlashdata('msg', 'The username already exists. Please choose another one.');
            return redirect()->to('/register')->withInput();  // Preserve the input values
        }


        $existingEmail = $model->where('email', $this->request->getPost('email'))->first();
        if ($existingEmail) {
            $session->setFlashdata('msg', 'The email is already registered. Please use a different email address.');
            return redirect()->to('/register')->withInput();  // Preserve the input values
        }


        $student_id = 'SDCA' . strtoupper(bin2hex(random_bytes(2)));  // Generates a random 4-character string like H5J7


        $data = [
            'student_id' => $student_id,  // Add student_id here
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),  // Hash password
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'course' => $this->request->getPost('course'),
            'year' => $this->request->getPost('year'),
            'role' => 'student',
            'email' => $this->request->getPost('email'),  // Added email to the data array
        ];

        $create = $model->createUser($data);

        if ($create) {

            $session->set('user_id', $model->insertID());  // Store the user ID in the session


            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $otpExpiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $token = bin2hex(random_bytes(16)); // Generate a secure random token
            $tokenExpiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));


            $otpData = [
                'user_id' => $session->get('user_id'),
                'otp' => $otp, // Store OTP as a string
                'otp_expiration' => $otpExpiration,
                'token' => $token,
                'token_expiration' => $tokenExpiration,
                'is_verified' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'email' => $data['email'],
            ];

            $otpModel->save($otpData);


            $this->sendVerificationEmail($data['email'], $otp, $token);


            $session->setFlashdata('success', 'Registration Successful! You can now check your email for the verification code.');
            return redirect()->to('/verify-otp');
        } else {

            $session->setFlashdata('msg', 'Failed to register the student. Please try again later.');
            return redirect()->to('/register');
        }
    } else {

        if ($validation->hasError('confirm_password')) {
            $session->setFlashdata('msg', 'Passwords do not match. Please try again.');
            return redirect()->to('/register')->withInput();
        }
    

        $session->setFlashdata('msg', 'There are errors in the form. Please correct them and try again.');
        return redirect()->to('/register')->withInput()->with('validation', $validation);
    }
}




    public function getBookDetails()
{
    $book_id = $this->request->getGet('book_id');
    
    if (!$book_id) {
        return $this->response->setJSON(['error' => 'Book ID is required']);
    }


    $bookModel = new BookModel();
    $transactionModel = new TransactionModel();


    $book = $bookModel->find($book_id);
    if (!$book) {
        return $this->response->setJSON(['error' => 'Book not found']);
    }


    $history = $transactionModel->select('users.firstname, users.lastname, transactions.borrow_date')
                                ->join('users', 'users.user_id = transactions.user_id')
                                ->where('transactions.book_id', $book_id)
                                ->orderBy('transactions.borrow_date', 'DESC') // Sorting by borrow date in descending order
                                ->findAll();


    $history = array_map(function($item) {
        return [
            'user' => $item['firstname'] . ' ' . $item['lastname'],  // Concatenate firstname and lastname
            'date' => $item['borrow_date']  // Keep the borrow date as is
        ];
    }, $history);


    log_message('debug', 'Borrow History: ' . print_r($history, true));


    return $this->response->setJSON([
        'book' => $book,
        'history' => $history
    ]);
}



    
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
