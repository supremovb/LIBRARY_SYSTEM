<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\PasswordResetModel;
use App\Models\TransactionModel;
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
        // To prevent email enumeration, always show a success message
        return redirect()->back()->with('success', 'If that email address exists in our system, we have sent a password reset link to it.');
    }

    try {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Set token expiration to 5 minutes from now
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Insert token into password_resets table
        $this->passwordResetModel->insert([
            'user_id' => $user['user_id'],
            'token' => $token,
            'expires_at' => $expires_at
        ]);

        // Prepare the reset link
        $resetLink = base_url("reset-password?token={$token}");

        // Prepare data to pass to the email view
        $data = [
            'firstname' => $user['firstname'], // Passing only the firstname
            'resetLink' => $resetLink
        ];

        // Load the email content from the view
        $message = view('emails/password_reset', $data);

        // Send the reset email
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

    // Check if the token has expired
    if (strtotime($resetRequest['expires_at']) < time()) {
        $this->passwordResetModel->delete($resetRequest['id']); // Remove expired token
        return redirect()->to('/login')->with('error', 'The password reset link has expired. Please request a new one.');
    }

    // Pass token to the view
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
            // Token has expired
            $this->passwordResetModel->delete($resetRequest['id']);
            return redirect()->to('/login')->with('error', 'Password reset token has expired.');
        }

        // Update the user's password
        $user = $this->userModel->find($resetRequest['user_id']);
        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found.');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->userModel->update($user['user_id'], ['password' => $hashedPassword]);

        // Delete the reset request
        $this->passwordResetModel->delete($resetRequest['id']);

        return redirect()->to('/login')->with('success', 'Your password has been reset successfully. You can now log in.');
    }

    
    public function login()
{
    $session = session();

    // Check if the user is logged in
    if ($session->get('logged_in')) {
        // Check for session timeout (30 minutes = 1800 seconds)
        $currentTime = time();
        $lastActivity = $session->get('last_activity') ?? $currentTime;

        if (($currentTime - $lastActivity) > 1800) { // 30 minutes timeout
            $session->destroy(); // Destroy the session
            $session->setFlashdata('msg', 'Your session has expired. Please log in again.');
            return redirect()->to('/login');
        }

        // Update the last activity timestamp
        $session->set('last_activity', $currentTime);

        // Redirect to the appropriate dashboard
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

        // Check if user is logged in and has the correct role
        if (!$session->get('logged_in') || $session->get('role') != 'student') {
            return redirect()->to('/');
        }

        $user_id = $session->get('user_id');
        $userModel = new UserModel();

        // Fetch the user data from the database
        $data['user'] = $userModel->find($user_id);

        // Return the view with the user's data
        return view('student/view_profile', $data);
    }

    public function updateProfile()
{
    $session = session();
    $userModel = new UserModel();

    if (!$session->get('logged_in') || $session->get('role') != 'student') {
        return redirect()->to('/');
    }

    $user_id = $session->get('user_id');
    $data = $this->request->getPost();

    // Get current user data
    $currentUser = $userModel->find($user_id);
    if (!$currentUser) {
        log_message('error', 'User not found: ' . $user_id);
        return redirect()->to('student/view-profile')->with('error', 'User not found.');
    }

    // Check if username is present and changed. If not, don't validate it.
    if (isset($data['username']) && $currentUser['username'] === $data['username']) {
        unset($data['username']); // Don't update username if it's not changed
    }

    // Log the data being passed for debugging
    log_message('debug', 'User data for update: ' . print_r($data, true));

    // Validation
    $validation = \Config\Services::validation();
    $validationRules = [
        'firstname' => 'required|min_length[3]|max_length[100]',
        'lastname'  => 'required|min_length[3]|max_length[100]',
        'username'  => 'required|min_length[3]|max_length[50]',  // Only validate if username is provided (and changed)
        'new_password' => 'permit_empty|min_length[6]|max_length[255]',
        'confirm_password' => 'permit_empty|matches[new_password]',
        'course' => 'required|min_length[3]|max_length[100]',
        'year' => 'required|min_length[1]|max_length[10]',
    ];

    if (!isset($data['username']) || $currentUser['username'] === $data['username']) {
        $validationRules['username'] = 'permit_empty'; // Allow empty value for username
    }

    $validation->setRules($validationRules);

    if (!$validation->run($data)) {
        log_message('error', 'Validation Errors: ' . print_r($validation->getErrors(), true));
        return redirect()->to('student/view-profile')->with('error', 'Validation failed.');
    }

    // Handle Photo Upload
    $photo = $this->request->getFile('photo');
if ($photo && $photo->isValid()) {
    $photoName = $photo->getRandomName();
    $uploadPath = ROOTPATH . 'uploads/user_photos';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    $photo->move($uploadPath, $photoName);

    // Generate a URL for the photo
    $data['photo'] = base_url('uploads/user_photos/' . $photoName);
} else {
    unset($data['photo']);
}


    // Handle Password Update
    if (!empty($data['new_password']) && $data['new_password'] === $data['confirm_password']) {
        $data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
    } else {
        unset($data['password']); // Remove password if no change
    }

    unset($data['new_password'], $data['confirm_password'], $data['role']);
    $data['updated_at'] = date('Y-m-d H:i:s');

    // Try updating the user profile
    try {
        if (!$userModel->update($user_id, $data)) {
            // Get the errors as a string (imploded)
            $errors = implode(', ', $userModel->errors());
            // Log database error if update fails
            throw new \Exception($errors);
        }
        return redirect()->to('student/view-profile')->with('success', 'Profile updated successfully.');
    } catch (\Exception $e) {
        log_message('error', 'Update Error: ' . $e->getMessage());
        return redirect()->to('student/view-profile')->with('error', 'An error occurred while updating your profile.');
    }
}
    

    public function authenticate()
    {
        $session = session();
        $model = new UserModel();
        $username = trim($this->request->getVar('username')); // Trim input
        $password = trim($this->request->getVar('password')); // Trim input
    
        $data = $model->where('username', $username)->first();
    
        if ($data) {
            $hashedPassword = $data['password'];
            log_message('info', 'Password hash from DB: ' . $hashedPassword); // Log the hash from DB
    
            if (password_verify($password, $hashedPassword)) {
                // Set session data with user's firstname
                $ses_data = [
                    'user_id' => $data['user_id'],
                    'username' => $data['username'],
                    'role' => $data['role'],
                    'firstname' => $data['firstname'], // Store first name in session
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);
    
                if ($data['role'] === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/user/dashboard');
                }
            } else {
                log_message('error', 'Password mismatch for user: ' . $username);
                $session->setFlashdata('msg', 'Incorrect password.');
                return redirect()->to('/login');
            }
        } else {
            log_message('error', 'Username not found: ' . $username);
            $session->setFlashdata('msg', 'Username not found.');
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
            // Load available books for students
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
    
    // Join transactions and books tables to get borrowed books with details, including the photo and due date
    $borrowedBooks = $this->transactionModel
        ->select('transactions.transaction_id, transactions.borrow_date, transactions.due_date, books.title, books.author, books.isbn, books.published_date, books.photo')
        ->join('books', 'books.book_id = transactions.book_id')
        ->where('transactions.user_id', $user_id)
        ->where('transactions.status', 'borrowed')
        ->findAll();

    $data['borrowed'] = $borrowedBooks;

    // Load the view with borrowed books data
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
    
    // Validate the form inputs
    $validation = \Config\Services::validation();

    // Add validation rules for email
    $validation->setRules([
        'email' => 'required|valid_email|is_unique[users.email]',
        'username' => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username]',
        'password' => 'required|min_length[6]',
        'firstname' => 'required|alpha_space|min_length[2]|max_length[50]',
        'lastname' => 'required|alpha_space|min_length[2]|max_length[50]',
        'course' => 'required|alpha_space|min_length[2]|max_length[50]',
        'year' => 'required|alpha_numeric_space|min_length[1]|max_length[20]',
    ]);

    if ($this->validate('student_registration')) {
        // Check if the username already exists
        $existingUser = $model->where('username', $this->request->getPost('username'))->first();
        if ($existingUser) {
            $session->setFlashdata('msg', 'The username already exists. Please choose another one.');
            return redirect()->to('/register');
        }

        // Check if the email already exists
        $existingEmail = $model->where('email', $this->request->getPost('email'))->first();
        if ($existingEmail) {
            $session->setFlashdata('msg', 'The email is already registered. Please use a different email address.');
            return redirect()->to('/register');
        }

        // Attempt to create the student
        $data = [
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
            // Success: Registering a student
            $session->setFlashdata('success', 'Registration Successful! You can now log in.');
            return redirect()->to('/login');
        } else {
            // Error: Failed to register
            $session->setFlashdata('msg', 'Failed to register the student. Please try again later.');
            return redirect()->to('/register');
        }
    } else {
        // Validation failed, return to the registration form with validation errors
        return redirect()->to('/register')->withInput()->with('validation', $validation);
    }
}


    public function getBookDetails()
{
    $book_id = $this->request->getGet('book_id');
    
    if (!$book_id) {
        return $this->response->setJSON(['error' => 'Book ID is required']);
    }

    // Load necessary models
    $bookModel = new BookModel();
    $transactionModel = new TransactionModel();

    // Get book details
    $book = $bookModel->find($book_id);
    if (!$book) {
        return $this->response->setJSON(['error' => 'Book not found']);
    }

    // Get the borrow history for the book
    $history = $transactionModel->select('users.firstname, users.lastname, transactions.borrow_date')
                                ->join('users', 'users.user_id = transactions.user_id')
                                ->where('transactions.book_id', $book_id)
                                ->orderBy('transactions.borrow_date', 'DESC') // Sorting by borrow date in descending order
                                ->findAll();

    // Process the borrow history to create a user field
    $history = array_map(function($item) {
        return [
            'user' => $item['firstname'] . ' ' . $item['lastname'],  // Concatenate firstname and lastname
            'date' => $item['borrow_date']  // Keep the borrow date as is
        ];
    }, $history);

    // Log the borrow history to verify its contents
    log_message('debug', 'Borrow History: ' . print_r($history, true));

    // Return the details as a JSON response
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
