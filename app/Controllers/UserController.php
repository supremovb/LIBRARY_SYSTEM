<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\PasswordResetModel;
use App\Models\TransactionModel;
use App\Models\OtpModel;
use App\Models\CategoryModel;
use App\Models\BookReviewModel;
use CodeIgniter\Controller;

class UserController extends BaseController
{

    protected $transactionModel;
    protected $userModel;
    protected $passwordResetModel;
    protected $email;


    public function __construct()
    {


        $this->transactionModel = new TransactionModel(); 
        $this->userModel = new UserModel();
        $this->passwordResetModel = new PasswordResetModel();
        $this->email = \Config\Services::email();
    }

    public function submitReview()
    {
        $bookId = $this->request->getPost('book_id');
        $rating = $this->request->getPost('rating');
        $reviewText = $this->request->getPost('review_text');
        $userId = session()->get('user_id'); 

        $bookReviewModel = new \App\Models\BookReview();
        $review = $bookReviewModel->createReview($bookId, $userId, $rating, $reviewText);

        if ($review) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Review submitted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to submit review.']);
        }
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
        $token = $this->request->getGet('token'); 
        $otpModel = new \App\Models\OtpModel();


        $currentDateTime = date('Y-m-d H:i:s');
        $otpRecord = $otpModel
            ->where('token', $token)
            ->where('token_expiration >', $currentDateTime) 
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
        return view('verify_otp'); 
    }



    public function processOtp()
    {
        $session = session();
        $otpModel = new \App\Models\OtpModel();
        $userModel = new \App\Models\UserModel();  


        $currentDateTime = date('Y-m-d H:i:s'); 

        $userId = $session->get('user_id'); 
        log_message('debug', 'Session User ID: ' . $userId);

        if (!$userId) {
            log_message('debug', 'No User ID found in session.');
            $session->setFlashdata('msg', 'Session expired or invalid. Please log in again.');
            return redirect()->to('/login');
        }


        $otpRecord = $otpModel
            ->where('user_id', $userId)
            ->where('otp_expiration >', $currentDateTime) 
            ->first();


        if (!$otpRecord) {
            log_message('debug', 'OTP expired or invalid for user ID: ' . $userId);


            $userModel->delete($userId); 


            $otpModel->where('user_id', $userId)->delete();

            $session->setFlashdata('msg', 'OTP expired or invalid. The user has been deleted.');
            return redirect()->to('/register');  
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
        helper(['form', 'session']);  

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
                'firstname' => $user['firstname'], 
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
            $this->passwordResetModel->delete($resetRequest['id']); 
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

            
            if (($currentTime - $lastActivity) > 1800) { 
                $session->destroy(); 
                $session->setFlashdata('msg', 'Your session has expired. Please log in again.');
                return redirect()->to('/login');
            }

            
            $session->set('last_activity', $currentTime);

            
            if ($session->get('role') === 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif ($session->get('role') === 'librarian') {
                return redirect()->to('/librarian/dashboard'); 
            } else {
                return redirect()->to('/user/dashboard');
            }
        }

        helper(['form']);
        echo view('login');
    }


    public function adminDashboard()
    {
        $session = session();
        if ($session->get('role') !== 'admin') {
            return redirect()->to('/login');
        }
        
        echo view('admin/dashboard');
    }

    public function librarianDashboard()
    {
        $session = session();
        if ($session->get('role') !== 'librarian') {
            return redirect()->to('/login');
        }
        
        echo view('librarian/dashboard');
    }





    public function viewProfile()
    {
        $session = session();


        if (!$session->get('logged_in') || $session->get('role') != 'student') {
            return redirect()->to('/');
        }

        $user_id = $session->get('user_id');
        $userModel = new UserModel();
        $otpModel = new OtpModel();  


        $data['user'] = $userModel->find($user_id);

        if (!$data['user']) {
            return redirect()->to('/')->with('error', 'User not found.');
        }


        $emailVerification = $otpModel->where('user_id', $user_id)
            ->where('email', $data['user']['email'])
            ->first();


        if ($emailVerification && $emailVerification['is_verified'] == 1) {
            $data['emailVerified'] = true;  
        } else {
            $data['emailVerified'] = false; 
        }


        return view('student/view_profile', $data);
    }


    public function updateProfile()
    {
        $session = session();
        $userModel = new UserModel();
        $otpModel = new OtpModel(); 

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
        $studentIdChanged = isset($data['student_id']) && $currentUser['student_id'] !== $data['student_id'];


        if (!empty($data['new_password']) && $data['new_password'] !== $data['confirm_password']) {

            return redirect()->to('student/view-profile')->with('error', 'Passwords do not match!');
        }



        if (!$emailChanged) {
            unset($data['email']); 
        }


        if (isset($data['username']) && $currentUser['username'] === $data['username']) {
            unset($data['username']); 
        }

        if (!isset($data['student_id']) || $currentUser['student_id'] === $data['student_id']) {
            $validationRules['student_id'] = 'permit_empty'; 
        }

        if (isset($data['student_id']) && $currentUser['student_id'] === $data['student_id']) {
            unset($data['student_id']); 
        }


        $validation = \Config\Services::validation();


        $validationRules = [
            'firstname' => 'required|min_length[3]|max_length[100]',
            'lastname'  => 'required|min_length[3]|max_length[100]',
            'username'  => 'required|min_length[3]|max_length[50]',
            'student_id' => 'permit_empty|alpha_numeric|min_length[3]|max_length[20]',
            'email'     => 'permit_empty|valid_email|max_length[100]',  
            'new_password' => 'permit_empty|min_length[6]|max_length[255]',
            'confirm_password' => 'permit_empty|matches[new_password]',
            'course' => 'required|min_length[3]|max_length[100]',
            'year' => 'required|min_length[1]|max_length[10]',
        ];

        if (!isset($data['username']) || $currentUser['username'] === $data['username']) {
            $validationRules['username'] = 'permit_empty'; 
        }

        $validation->setRules($validationRules);

        if (!$studentIdChanged) {
            $validationRules['student_id'] = 'permit_empty'; 
        }


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
            unset($data['password']); 
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

                $verificationToken = bin2hex(random_bytes(32)); 
                $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour')); 


                $otpData = [
                    'user_id' => $user_id,
                    'email' => $data['email'],
                    'token' => $verificationToken,
                    'token_expiration' => $tokenExpiration,
                    'type' => 'email_verification' 
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

        $input = trim($this->request->getVar('username'));
        $password = trim($this->request->getVar('password'));
        $role = trim($this->request->getVar('role')); 

        if (empty($input) || empty($password) || empty($role)) {
            $session->setFlashdata('msg', 'All fields are required.');
            return redirect()->to('/login');
        }

        $data = $model->where('username', $input)->orWhere('student_id', $input)->first();

        if ($data) {
            if (password_verify($password, $data['password'])) {
                
                if ($data['role'] !== $role) {
                    $session->setFlashdata('msg', 'You are not authorized as the selected role.');
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

                
                if ($role === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } elseif ($role === 'student') {
                    return redirect()->to('/user/dashboard');
                } elseif ($role === 'librarian') {
                    return redirect()->to('/librarian/dashboard'); 
                } else {
                    $session->setFlashdata('msg', 'Invalid role selected.');
                    return redirect()->to('/login');
                }
            } else {
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Username/Student ID not found.');
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
        } elseif (!$session->get('role') == 'librarian') {
            return redirect()->to('/login');
        } else {
            $bookModel = new BookModel();
            $categoryModel = new CategoryModel();
            $transactionModel = new TransactionModel();

            
            $userId = $session->get('user_id');

            
            $borrowedCategories = $transactionModel->getUserBorrowedCategories($userId);

            
            if (!empty($borrowedCategories)) {
                $data['relatedBooks'] = $bookModel->getRelatedBooksByCategories($borrowedCategories);
            } else {
                $data['relatedBooks'] = [];  
            }

            
            if (!empty($borrowedCategories)) {
                $data['recommendedBooks'] = $bookModel->getBooksByCategories($borrowedCategories, $userId);
            } else {
                $data['recommendedBooks'] = $bookModel->getBooksByCategories([], $userId);  
            }

            
            $data['books'] = $bookModel->findAll();

            
            $data['categories'] = $categoryModel->findAll();

            return view('student/dashboard', $data);
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
            'email' => [
                'required',
                'valid_email',
                'is_unique[users.email]',
                'regex_match[/^[a-zA-Z0-9._%+-]+@sdca\.edu\.ph$/]'  
            ],
            'username' => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'firstname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'lastname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'course' => 'required|alpha_space|min_length[2]|max_length[50]',
            'year' => 'required|alpha_numeric_space|min_length[1]|max_length[20]',
        ]);

        if ($this->validate('student_registration')) {

            $existingUser = $model->where('username', $this->request->getPost('username'))->first();
            if ($existingUser) {
                $session->setFlashdata('msg', 'The username already exists. Please choose another one.');
                return redirect()->to('/register')->withInput();  
            }

            $existingEmail = $model->where('email', $this->request->getPost('email'))->first();
            if ($existingEmail) {
                $session->setFlashdata('msg', 'The email is already registered. Please use a different email address.');
                return redirect()->to('/register')->withInput();  
            }

            
            $email = $this->request->getPost('email');
            if (!preg_match('/@sdca\.edu\.ph$/', $email)) {
                $session->setFlashdata('msg', 'Please register with an email address from the SDCA');
                return redirect()->to('/register')->withInput();  
            }

            $student_id = 'SDCA' . strtoupper(bin2hex(random_bytes(2)));  

            $data = [
                'student_id' => $student_id,
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'firstname' => $this->request->getPost('firstname'),
                'lastname' => $this->request->getPost('lastname'),
                'course' => $this->request->getPost('course'),
                'year' => $this->request->getPost('year'),
                'role' => 'student',
                'email' => $this->request->getPost('email'),  
            ];

            $create = $model->createUser($data);

            if ($create) {
                $session->set('user_id', $model->insertID());  

                $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $otpExpiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));

                $token = bin2hex(random_bytes(16)); 
                $tokenExpiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));

                $otpData = [
                    'user_id' => $session->get('user_id'),
                    'otp' => $otp,
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
            ->orderBy('transactions.borrow_date', 'DESC') 
            ->findAll();


        $history = array_map(function ($item) {
            return [
                'user' => $item['firstname'] . ' ' . $item['lastname'],  
                'date' => $item['borrow_date']  
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
