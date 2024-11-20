<?php

namespace App\Controllers;

use App\Models\BookModel;
use CodeIgniter\Controller;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\OtpModel;
use App\Models\CategoryModel;



class AdminController extends BaseController
{

    protected $categoryModel;

    public function __construct()
    {
        // Initialize the CategoryModel
        $this->categoryModel = new CategoryModel();
    }


    // Method to display the form
    public function add_Category()
    {
        return view('admin/add_category');
    }

    public function addCategory()
{
    $categoryModel = new CategoryModel();

    // Validate input
    $validation = \Config\Services::validation();
    $validation->setRules([
        'name' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[5]'
    ]);

    if (!$this->validate($validation->getRules())) {
        // Validation failed
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Insert category into the database
    $data = [
        'name' => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
    ];

    if ($categoryModel->insert($data)) {
        // Success: Redirect with success message
        return redirect()->to('/admin/categories')->with('success', 'Category added successfully!');
    } else {
        // Failure: Redirect with error message
        return redirect()->back()->withInput()->with('error', 'Failed to add category. Please try again.');
    }
}


public function editCategory($categoryId)
    {
        // Fetch the category by ID from the database
        $category = $this->categoryModel->find($categoryId);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Category with ID $categoryId not found");
        }

        // Pass the category data to the view
        return view('admin/edit_category', ['category' => $category]);
    }


public function updateCategory()
{
    $categoryId = $this->request->getPost('category_id');
    $name = $this->request->getPost('name');
    $description = $this->request->getPost('description');

    $categoryModel = new CategoryModel();

    $categoryData = [
        'name' => $name,
        'description' => $description
    ];

    if ($categoryModel->update($categoryId, $categoryData)) {
        session()->setFlashdata('success', 'Category updated successfully!');
    } else {
        session()->setFlashdata('error', 'An error occurred while updating the category.');
    }

    return redirect()->to(base_url('admin/edit-category/' . $categoryId));
}





public function categories()
{
    $categoryModel = new \App\Models\CategoryModel();
    $data['categories'] = $categoryModel->findAll(); // Fetch all categories from the database

    return view('admin/categories', $data); // Load the categories view
}


public function deleteCategory($categoryId)
    {
        // Load the Category model
        $categoryModel = new CategoryModel();

        // Check if the category exists
        $category = $categoryModel->find($categoryId);

        if ($category) {
            // Delete the category
            $categoryModel->delete($categoryId);

            // Set success message and redirect back to categories page
            return redirect()->to(base_url('admin/categories'))->with('message', 'Category deleted successfully.');
        } else {
            // Set error message and redirect back to categories page if category is not found
            return redirect()->to(base_url('admin/categories'))->with('error', 'Category not found.');
        }
    }



    public function viewProfile()
{
    $session = session();

    // Check if the user is logged in and has the required role/permissions
    if (!session()->has('logged_in') || session()->get('role') !== 'admin') {
        // Redirect to an unauthorized page or show a warning
        return view('errors/unauthorized'); // Create this view to show an unauthorized message
    }

    $user_id = $session->get('user_id');
    $userModel = new UserModel();
    $otpModel = new OtpModel();
    
    // Fetch the user data from the database
    $data['user'] = $userModel->find($user_id);

    if (!$data['user']) {
        return redirect()->to('/')->with('error', 'User not found.');
    }

    // Check if the user has an OTP record for email verification
    $emailVerification = $otpModel->where('user_id', $user_id)
                                   ->where('email', $data['user']['email'])
                                   ->first();

    // Set email verification status
    if ($emailVerification && $emailVerification['is_verified'] == 1) {
        $data['emailVerified'] = true;  // Email is verified
    } else {
        $data['emailVerified'] = false; // Email is not verified
    }

    // Pass data to the view
    return view('admin/view_profile', $data);
}




    public function updateProfile()
{
    $session = session();
    $userModel = new UserModel();
    $otpModel = new OtpModel();

    // Check if user is logged in and has the admin role
    if (!$session->get('logged_in') || $session->get('role') !== 'admin') {
        return redirect()->to('/')->with('error', 'Access denied.');
    }

    $user_id = $session->get('user_id');
    $data = $this->request->getPost();

    // Get current user data
    $currentUser = $userModel->find($user_id);
    if (!$currentUser) {
        return redirect()->to('admin/view-profile')->with('error', 'User not found.');
    }

    // Check if the email has been changed
    $emailChanged = isset($data['email']) && $currentUser['email'] !== $data['email'];

    // Handle Password Update
    if (!empty($data['new_password']) && $data['new_password'] !== $data['confirm_password']) {
        return redirect()->to('student/view-profile')->with('error', 'Passwords do not match!');
    }

    // If email hasn't changed, remove it from the data array (do not update email)
    if (!$emailChanged) {
        unset($data['email']); // Don't update the email if it's not changed
    }

    // Check if username is unchanged
    if (isset($data['username']) && $currentUser['username'] === $data['username']) {
        unset($data['username']); // Don't update username if it's not changed
    }

    // Validation
    $validation = \Config\Services::validation();
    $validationRules = [
        'firstname' => 'required|min_length[3]|max_length[100]',
        'lastname'  => 'required|min_length[3]|max_length[100]',
        'username'  => 'required|min_length[3]|max_length[50]',
        'email'     => 'permit_empty|valid_email|max_length[100]',
        'new_password' => 'permit_empty|min_length[6]|max_length[255]',
        'confirm_password' => 'permit_empty|matches[new_password]',
    ];

    if (!isset($data['username']) || $currentUser['username'] === $data['username']) {
        $validationRules['username'] = 'permit_empty'; // Allow empty value for username
    }

    $validation->setRules($validationRules);

    // Run validation
    if (!$validation->run($data)) {
        // Log validation errors
        $validationErrors = $validation->getErrors();
        log_message('error', 'Validation Errors: ' . print_r($validationErrors, true));

        // Pass validation errors to the session
        return redirect()->to('admin/view-profile')
            ->with('error', 'Validation failed. Please fix the errors below.')
            ->with('validationErrors', $validationErrors);
    }

    // Handle photo upload
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

    // Handle Password Update
    if (!empty($data['new_password']) && $data['new_password'] === $data['confirm_password']) {
        $data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
    } else {
        unset($data['password']); // Remove password if no change
    }

    unset($data['new_password'], $data['confirm_password'], $data['role']);
    $data['updated_at'] = date('Y-m-d H:i:s');

    // Update session data if firstname is changed
    if (isset($data['firstname'])) {
        $session->set('firstname', $data['firstname']);
    }

    // Handle email change and OTP generation
    if ($emailChanged) {
        // Generate a unique token for email verification
        $verificationToken = bin2hex(random_bytes(32)); // Generate a random token
        $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid for 1 hour

        // Save the token and its expiration to the user_otps table
        $otpData = [
            'user_id' => $user_id,
            'email' => $data['email'],
            'token' => $verificationToken,
            'token_expiration' => $tokenExpiration,
            'type' => 'email_verification' // Distinguish OTP types
        ];

        if (!$otpModel->save($otpData)) {
            log_message('error', 'Failed to save OTP in the database.');
            return redirect()->to('admin/view-profile')->with('error', 'Failed to generate OTP for email verification.');
        }

        // Send the verification email with the OTP
        $verificationLink = base_url("verify-email?token=$verificationToken");

        $email = \Config\Services::email();
        $email->setTo($data['email']);
        $email->setSubject('Verify Your Email');
        $email->setMessage("Click the link below to verify your email:\n\n" . $verificationLink);

        if (!$email->send()) {
            log_message('error', 'Email sending failed: ' . $email->printDebugger());
            return redirect()->to('admin/view-profile')->with('error', 'Failed to send verification email.');
        }
    }

    // Update the user profile
    if ($userModel->update($user_id, $data)) {
        // Redirect with a success message and prompt for email verification
        return redirect()->to('admin/view-profile')->with('success', 'Profile updated successfully. Please verify your new email address.');
    } else {
        log_message('error', 'Database Update Failed: ' . print_r($userModel->errors(), true));
        return redirect()->to('admin/view-profile')->with('error', 'Failed to update profile.');
    }
}

public function verifyEmail()
{
    $token = $this->request->getGet('token');
    $otpModel = new OtpModel();
    $userModel = new UserModel();

    // Check if the token is valid
    $otp = $otpModel->where('token', $token)->first();
    if (!$otp || strtotime($otp['token_expiration']) < time()) {
        return redirect()->to('/')->with('error', 'Invalid or expired verification token.');
    }

    // Update user email and mark OTP as verified
    $user_id = $otp['user_id'];
    $user = $userModel->find($user_id);
    $userModel->update($user_id, ['email' => $otp['new_email']]);

    // Remove the OTP record
    $otpModel->delete($otp['id']);

    // Success message
    return redirect()->to('/')->with('success', 'Your email has been successfully verified!');
}




    // Dashboard showing all books
    public function dashboard()
    {
        $session = session();

        // Ensure the user is logged in
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Check if the user has the admin role
        if ($session->get('role') !== 'admin') {
            return redirect()->to('/user/dashboard');
        }

        // Fetch all books from the BookModel
        $bookModel = new BookModel();
        $data['books'] = $bookModel->findAll();  // Get all books

        // Pass data to the view (book_list.php)
        return view('admin/book_list', $data);
    }

    // Show form to add a new book
    public function addBook()
    {
        // Check if the user is logged in and has the required role/permissions
        if (!session()->has('logged_in') || session()->get('role') !== 'admin') {
            // Redirect to an unauthorized page or show a warning
            return view('errors/unauthorized'); // Create this view to show an unauthorized message
        }
    
        // Fetch categories from the database
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->findAll();
    
        // Pass categories to the view
        return view('admin/book_form', ['categories' => $categories]);
    }
    


    // Store new book
    public function createBook()
{
    try {
        $file = $this->request->getFile('photo');

        // Validate file input
        if (!$file || !$file->isValid()) {
            throw new \RuntimeException($file ? $file->getErrorString() : 'No file uploaded.');
        }

        // Allowed file extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower($file->getExtension());

        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new \RuntimeException('Invalid file type: ' . $fileExtension);
        }

        // Ensure upload directory exists
        $uploadPath = FCPATH . 'uploads/books/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate random file name and move the file
        $newFileName = $file->getRandomName();
        if (!$file->move($uploadPath, $newFileName)) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        // Collect input data
        $bookData = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'isbn' => $this->request->getPost('isbn'),
            'published_date' => $this->request->getPost('published_date'),
            'description' => $this->request->getPost('description'), // Add description here
            'photo' => $newFileName,
            'category_id' => $this->request->getPost('category'), // Get category from form
        ];

        // Validate required fields
        foreach ($bookData as $key => $value) {
            if (empty($value) && $key !== 'photo') {
                throw new \RuntimeException("The {$key} field is required.");
            }
        }

        // Insert data into the database
        $bookModel = new BookModel();

        // Temporarily disable validation for 'status' if not set
        $bookModel->skipValidation(true);
        $inserted = $bookModel->insert($bookData);
        $bookModel->skipValidation(false); // Re-enable validation

        if (!$inserted) {
            $errors = $bookModel->errors();
            throw new \RuntimeException(
                'Failed to insert book into the database. Errors: ' . json_encode($errors)
            );
        }

        // Success response
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Book created successfully.',
        ]);
    } catch (\Exception $e) {
        // Log error for debugging
        log_message('error', $e->getMessage());

        // Error response
        return $this->response->setJSON([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
}



public function approveTransaction($transactionId)
{
    $transactionModel = new \App\Models\TransactionModel();
    $bookModel = new \App\Models\BookModel();
    $dueDate = $this->request->getPost('due_date'); // Get due date from the form

    // Check if the due date is provided
    if (empty($dueDate)) {
        session()->setFlashdata('message', 'Please provide a valid due date.');
        return redirect()->back();
    }

    // Fetch the transaction details to get the associated book ID
    $transaction = $transactionModel->find($transactionId);
    if (!$transaction) {
        session()->setFlashdata('message', 'Transaction not found.');
        return redirect()->to('/admin/approve_reject_transactions');
    }

    // Update the transaction and book status
    $db = \Config\Database::connect();
    $db->transStart();

    $transactionModel->update($transactionId, [
        'status' => 'borrowed',
        'due_date' => $dueDate,
    ]);
    $bookModel->update($transaction['book_id'], ['status' => 'borrowed']);

    $db->transComplete();

    if ($db->transStatus() === false) {
        session()->setFlashdata('message', 'Failed to approve the transaction.');
    } else {
        session()->setFlashdata('message', 'Transaction approved successfully.');
    }

    return redirect()->to('/admin/approve_reject_transactions');
}

public function rejectTransaction($transactionId)
{
    $transactionModel = new \App\Models\TransactionModel();
    $bookModel = new \App\Models\BookModel();

    // Fetch the transaction details to get the associated book ID
    $transaction = $transactionModel->find($transactionId);
    if (!$transaction) {
        session()->setFlashdata('message', 'Transaction not found.');
        return redirect()->to('/admin/approve_reject_transactions');
    }

    // Update the transaction and book status
    $db = \Config\Database::connect();
    $db->transStart();

    $transactionModel->update($transactionId, ['status' => 'rejected']);
    $bookModel->update($transaction['book_id'], ['status' => 'available']);

    $db->transComplete();

    if ($db->transStatus() === false) {
        session()->setFlashdata('message', 'Failed to reject the transaction.');
    } else {
        session()->setFlashdata('message', 'Transaction rejected successfully.');
    }

    return redirect()->to('/admin/approve_reject_transactions');
}



public function approve_reject_transactions()
{
    // Check if the user is logged in and has the required role/permissions
    if (!session()->has('logged_in') || session()->get('role') !== 'admin') {
        return view('errors/unauthorized');
    }

    // Load the Transaction model
    $transactionModel = new TransactionModel();

    // Fetch the pending transactions, joining with the users and books table
    $builder = $transactionModel->builder();
    $builder->select('transactions.*, users.firstname, users.lastname, books.title');  // Ensure 'books.title' is selected
    $builder->join('users', 'users.user_id = transactions.user_id');
    $builder->join('books', 'books.book_id = transactions.book_id');  // Join with books table
    $builder->where('transactions.status', 'pending');
    $data['pendingTransactions'] = $builder->get()->getResult();

    // Pass the data to the view
    return view('admin/approve_reject_transactions', $data);
}



    

    // Show the form to edit a book
public function editBook($book_id)
{
    $bookModel = new BookModel();
    $categoryModel = new CategoryModel();

    // Fetch the book to edit
    $data['book'] = $bookModel->find($book_id);

    // Fetch all categories to show in the form
    $data['categories'] = $categoryModel->findAll();

    

    // Load the book edit form with the current book data and categories
    return view('admin/book_edit', $data);  // Load the book_edit.php view
}


    // Update book details
    public function updateBookDetails()
    {
        $bookModel = new BookModel();
        
        // Collect updated data
        $book_id = $this->request->getPost('book_id');
        $title = $this->request->getPost('title');
        $isbn = $this->request->getPost('isbn');  // Still get the ISBN but won't validate if unchanged
    
        // If the title is updated, proceed to validate it
        $validationRules = [
            'title' => 'required|min_length[3]|max_length[255]',
        ];
    
        // Get existing book data to compare ISBN
        $existingBookData = $bookModel->find($book_id);
        $existingIsbn = $existingBookData ? $existingBookData['isbn'] : '';
    
        // Only validate ISBN if it's updated (i.e., if a new ISBN is provided or if it differs from the current one)
        if ($isbn && $isbn !== $existingIsbn) {
            $validationRules['isbn'] = 'required|is_unique[books.isbn,book_id,' . $book_id . ']|min_length[10]|max_length[13]';
        }
    
        // Validate other fields conditionally
        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed. Please check the inputs.',
                'errors' => \Config\Services::validation()->getErrors(),
            ]);
        }
    
        // Prepare the data to update
        $data = [
            'title' => $title,
            'isbn' => $isbn,
            'author' => $this->request->getPost('author'),
            'published_date' => $this->request->getPost('published_date'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'), // Optional field
            'category_id' => $this->request->getPost('category'), // Update the category_id if provided
        ];
        
        // Handle photo upload if provided
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid()) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower($file->getExtension());
    
            if (!in_array($fileExtension, $allowedExtensions)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid file type for the photo.',
                ]);
            }
    
            // Ensure upload directory exists
            $uploadPath = FCPATH . 'uploads/books/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
    
            // Move the file to the upload directory
            $newFileName = $file->getRandomName();
            if (!$file->move($uploadPath, $newFileName)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to upload the photo.',
                ]);
            }
    
            // Add photo to the update data
            $data['photo'] = $newFileName;
    
            // Optionally delete the old photo
            if ($existingBookData && !empty($existingBookData['photo'])) {
                @unlink($uploadPath . $existingBookData['photo']);
            }
        }
    
        // Update the book record
        if ($bookModel->update($book_id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Book updated successfully.',
            ]);
        } else {
            // Log model errors
            log_message('error', 'Failed to update book. Model error: ' . print_r($bookModel->errors(), true));
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update book.',
            ]);
        }
    }
    


    public function viewBooks()
    {
        if (!session()->has('logged_in') || session()->get('role') !== 'admin') {
            return view('errors/unauthorized');
        }
    
        $bookModel = new BookModel();
    
        // Use a join to fetch category name along with book details
        $books = $bookModel->select('books.*, categories.name as category_name')
                           ->join('categories', 'books.category_id = categories.category_id', 'left')
                           ->findAll();
    
        return view('admin/view_books', ['books' => $books]);
    }
    
    
    
    // Delete a book
    // Delete a book
    public function deleteBook($book_id)
    {
        $bookModel = new BookModel();
    
        // Check if the book exists
        if (!$bookModel->find($book_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Book not found'
            ]);
        }
    
        // Attempt to delete the book
        if ($bookModel->delete($book_id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Book deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete book'
            ]);
        }
    }
    


    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
