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

        $this->categoryModel = new CategoryModel();
    }

    public function viewUsers()
    {

        $userModel = new \App\Models\UserModel();


        $users = $userModel->findAll();


        return view('admin/view_users', ['users' => $users]);
    }


    public function editRole()
    {
        $userModel = new UserModel();

        $request = $this->request->getJSON();
        $userId = $request->user_id;
        $role = $request->role;

        $data = ['role' => $role];

        if ($userModel->update($userId, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User role updated successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update user role. Please try again.'
            ]);
        }
    }


    public function deleteUser()
    {

        $request = $this->request->getJSON();
        $user_id = $request->user_id;


        $model = new UserModel();
        $user = $model->find($user_id);

        if ($user) {
            $model->delete($user_id);
            return $this->response->setJSON(['success' => true, 'message' => 'User deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found.']);
        }
    }






    public function add_Category()
    {
        return view('admin/add_category');
    }

    public function addCategory()
    {
        $categoryModel = new CategoryModel();


        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[5]'
        ]);

        if (!$this->validate($validation->getRules())) {

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($categoryModel->insert($data)) {

            return redirect()->to('/admin/categories')->with('success', 'Category added successfully!');
        } else {

            return redirect()->back()->withInput()->with('error', 'Failed to add category. Please try again.');
        }
    }



    public function editCategory($categoryId)
    {

        $category = $this->categoryModel->find($categoryId);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Category with ID $categoryId not found");
        }


        return view('admin/edit_category', ['category' => $category]);
    }


    public function updateCategory()
    {

        $categoryId = $this->request->getPost('category_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');


        $category = $this->categoryModel->find($categoryId);
        if (!$category) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Category not found.']);
        }


        $categoryData = [
            'name' => $name,
            'description' => $description,
        ];


        if ($this->categoryModel->update($categoryId, $categoryData)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Category updated successfully!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'An error occurred while updating the category.']);
        }
    }







    public function categories()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $data['categories'] = $categoryModel->findAll(); // Fetch all categories from the database

        return view('admin/categories', $data); // Load the categories view
    }


    public function deleteCategory($categoryId)
    {

        $categoryModel = new CategoryModel();


        $category = $categoryModel->find($categoryId);

        if ($category) {

            $categoryModel->delete($categoryId);


            session()->setFlashdata('message', 'Category deleted successfully.');


            return redirect()->to(base_url('admin/categories'));
        } else {

            session()->setFlashdata('error', 'Category not found.');


            return redirect()->to(base_url('admin/categories'));
        }
    }




    public function viewProfile()
    {
        $session = session();


        if (!session()->has('logged_in') || session()->get('role') !== 'admin') {

            return view('errors/unauthorized'); // Create this view to show an unauthorized message
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
            $data['emailVerified'] = true;  // Email is verified
        } else {
            $data['emailVerified'] = false; // Email is not verified
        }


        return view('admin/view_profile', $data);
    }




    public function updateProfile()
    {
        $session = session();
        $userModel = new UserModel();
        $otpModel = new OtpModel();


        if (!$session->get('logged_in') || $session->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Access denied.');
        }

        $user_id = $session->get('user_id');
        $data = $this->request->getPost();


        $currentUser = $userModel->find($user_id);
        if (!$currentUser) {
            return redirect()->to('admin/view-profile')->with('error', 'User not found.');
        }



        $emailChanged = isset($data['email']) && $currentUser['email'] !== $data['email'];

        $studentIdChanged = isset($data['student_id']) && $currentUser['student_id'] !== $data['student_id'];


        if (!empty($data['new_password']) && $data['new_password'] !== $data['confirm_password']) {
            return redirect()->to('student/view-profile')->with('error', 'Passwords do not match!');
        }


        if (!$emailChanged) {
            unset($data['email']); // Don't update the email if it's not changed
        }


        if (isset($data['username']) && $currentUser['username'] === $data['username']) {
            unset($data['username']); // Don't update username if it's not changed
        }


        if (isset($data['student_id']) && $currentUser['student_id'] === $data['student_id']) {
            unset($data['student_id']); // Don't update username if it's not changed
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
        ];


        if (!$studentIdChanged) {
            $validationRules['student_id'] = 'permit_empty'; // Allow student_id to remain unchanged
        }

        if (!isset($data['username']) || $currentUser['username'] === $data['username']) {
            $validationRules['username'] = 'permit_empty'; // Allow empty value for username
        }

        if (!isset($data['student_id']) || $currentUser['student_id'] === $data['student_id']) {
            $validationRules['student_id'] = 'permit_empty'; // Allow empty value for username
        }

        $validation->setRules($validationRules);


        if (!$validation->run($data)) {

            $validationErrors = $validation->getErrors();
            log_message('error', 'Validation Errors: ' . print_r($validationErrors, true));


            return redirect()->to('admin/view-profile')
                ->with('error', 'Validation failed. Please fix the errors below.')
                ->with('validationErrors', $validationErrors);
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
                'type' => 'email_verification' // Distinguish OTP types
            ];

            if (!$otpModel->save($otpData)) {
                log_message('error', 'Failed to save OTP in the database.');
                return redirect()->to('admin/view-profile')->with('error', 'Failed to generate OTP for email verification.');
            }


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


        if ($userModel->update($user_id, $data)) {

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


        $otp = $otpModel->where('token', $token)->first();
        if (!$otp || strtotime($otp['token_expiration']) < time()) {
            return redirect()->to('/')->with('error', 'Invalid or expired verification token.');
        }


        $user_id = $otp['user_id'];
        $user = $userModel->find($user_id);
        $userModel->update($user_id, ['email' => $otp['new_email']]);


        $otpModel->delete($otp['id']);


        return redirect()->to('/')->with('success', 'Your email has been successfully verified!');
    }





    public function dashboard()
    {
        $session = session();


        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }


        if ($session->get('role') !== 'admin') {
            return redirect()->to('/user/dashboard');
        }


        $bookModel = new BookModel();
        $data['books'] = $bookModel->findAll();  // Get all books


        return view('admin/book_list', $data);
    }


    public function addBook()
    {

        if (!session()->has('logged_in') || session()->get('role') !== 'admin') {

            return view('errors/unauthorized'); // Create this view to show an unauthorized message
        }


        $categoryModel = new CategoryModel();
        $categories = $categoryModel->findAll();


        return view('admin/book_form', ['categories' => $categories]);
    }



    public function createBook()
    {
        try {
            $file = $this->request->getFile('photo');


            if (!$file || !$file->isValid()) {
                throw new \RuntimeException($file ? $file->getErrorString() : 'No file uploaded.');
            }


            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower($file->getExtension());

            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new \RuntimeException('Invalid file type: ' . $fileExtension);
            }


            $uploadPath = FCPATH . 'uploads/books/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }


            $newFileName = $file->getRandomName();
            if (!$file->move($uploadPath, $newFileName)) {
                throw new \RuntimeException('Failed to move uploaded file.');
            }


            $bookData = [
                'title' => $this->request->getPost('title'),
                'author' => $this->request->getPost('author'),
                'isbn' => $this->request->getPost('isbn'),
                'published_date' => $this->request->getPost('published_date'),
                'description' => $this->request->getPost('description'), // Add description here
                'photo' => $newFileName,
                'category_id' => $this->request->getPost('category'), // Get category from form
                'quantity' => $this->request->getPost('quantity'), // New quantity field
            ];


            foreach ($bookData as $key => $value) {
                if (empty($value) && $key !== 'photo') {
                    throw new \RuntimeException("The {$key} field is required.");
                }
            }


            $bookModel = new BookModel();


            $bookModel->skipValidation(true);
            $inserted = $bookModel->insert($bookData);
            $bookModel->skipValidation(false); // Re-enable validation

            if (!$inserted) {
                $errors = $bookModel->errors();
                throw new \RuntimeException(
                    'Failed to insert book into the database. Errors: ' . json_encode($errors)
                );
            }


            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Book created successfully.',
            ]);
        } catch (\Exception $e) {

            log_message('error', $e->getMessage());


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


        if (empty($dueDate)) {
            session()->setFlashdata('message', 'Please provide a valid due date.');
            return redirect()->back();
        }


        $transaction = $transactionModel->find($transactionId);
        if (!$transaction) {
            session()->setFlashdata('message', 'Transaction not found.');
            return redirect()->to('/admin/approve_reject_transactions');
        }

        $book = $bookModel->find($transaction['book_id']);
        if (!$book) {
            session()->setFlashdata('message', 'Book not found.');
            return redirect()->to('/admin/approve_reject_transactions');
        }


        $db = \Config\Database::connect();
        $db->transStart();


        $transactionModel->update($transactionId, [
            'status' => 'borrowed',
            'due_date' => $dueDate,
        ]);


        $newQuantity = $book['quantity'] - 1;
        $bookStatus = ($newQuantity > 0) ? 'available' : 'out of stock';
        $bookModel->update($transaction['book_id'], [
            'quantity' => $newQuantity,
            'status' => $bookStatus,
        ]);

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


        $transaction = $transactionModel->find($transactionId);
        if (!$transaction) {
            session()->setFlashdata('message', 'Transaction not found.');
            return redirect()->to('/admin/approve_reject_transactions');
        }

        $book = $bookModel->find($transaction['book_id']);
        if (!$book) {
            session()->setFlashdata('message', 'Book not found.');
            return redirect()->to('/admin/approve_reject_transactions');
        }


        $db = \Config\Database::connect();
        $db->transStart();


        $transactionModel->update($transactionId, ['status' => 'rejected']);








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

        if (!session()->has('logged_in') || session()->get('role') !== 'admin') {
            return view('errors/unauthorized');
        }


        $transactionModel = new TransactionModel();


        $builder = $transactionModel->builder();
        $builder->select('transactions.*, users.firstname, users.lastname, books.title');  // Ensure 'books.title' is selected
        $builder->join('users', 'users.user_id = transactions.user_id');
        $builder->join('books', 'books.book_id = transactions.book_id');  // Join with books table
        $builder->where('transactions.status', 'pending');
        $data['pendingTransactions'] = $builder->get()->getResult();


        return view('admin/approve_reject_transactions', $data);
    }

    public function approveAllTransactions()
    {
        $transactionModel = new \App\Models\TransactionModel();
        $bookModel = new \App\Models\BookModel();
        $dueDate = $this->request->getPost('due_date');

        if (empty($dueDate)) {
            session()->setFlashdata('message', 'Please provide a valid due date.');
            return redirect()->back();
        }


        $pendingTransactions = $transactionModel->where('status', 'pending')->findAll();

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($pendingTransactions as $transaction) {
            $book = $bookModel->find($transaction['book_id']);
            if (!$book || $book['quantity'] <= 0) {
                continue; // Skip if book is not found or out of stock
            }


            $transactionModel->update($transaction['transaction_id'], [
                'status' => 'borrowed',
                'due_date' => $dueDate,
            ]);


            $newQuantity = $book['quantity'] - 1;
            $bookModel->update($transaction['book_id'], [
                'quantity' => $newQuantity,
                'status' => ($newQuantity > 0) ? 'available' : 'out of stock',
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('message', 'Failed to approve all transactions.');
        } else {
            session()->setFlashdata('message', 'All transactions approved successfully.');
        }

        return redirect()->to('/admin/approve_reject_transactions');
    }

    public function rejectAllTransactions()
    {
        $transactionModel = new \App\Models\TransactionModel();
        $bookModel = new \App\Models\BookModel();


        $pendingTransactions = $transactionModel->where('status', 'pending')->findAll();

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($pendingTransactions as $transaction) {

            $transactionModel->update($transaction['transaction_id'], ['status' => 'rejected']);


            $book = $bookModel->find($transaction['book_id']);
            if ($book) {
                $newQuantity = $book['quantity'] + 1;
                $bookModel->update($transaction['book_id'], [
                    'quantity' => $newQuantity,
                    'status' => 'available',
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('message', 'Failed to reject all transactions.');
        } else {
            session()->setFlashdata('message', 'All transactions rejected successfully.');
        }

        return redirect()->to('/admin/approve_reject_transactions');
    }




    public function editBook($book_id)
    {
        $bookModel = new BookModel();
        $categoryModel = new CategoryModel();


        $data['book'] = $bookModel->find($book_id);


        $data['categories'] = $categoryModel->findAll();

        $data['book']['quantity'] = $bookModel->find($book_id)['quantity'];




        return view('admin/book_edit', $data);  // Load the book_edit.php view
    }



    public function updateBookDetails()
    {
        $bookModel = new BookModel();


        $book_id = $this->request->getPost('book_id');
        $title = $this->request->getPost('title');
        $isbn = $this->request->getPost('isbn');  // Still get the ISBN but won't validate if unchanged


        $validationRules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'quantity' => 'required|integer|greater_than_equal_to[0]',
        ];





        $existingBookData = $bookModel->find($book_id);
        $existingIsbn = $existingBookData ? $existingBookData['isbn'] : '';


        if ($isbn && $isbn !== $existingIsbn) {
            $validationRules['isbn'] = 'required|is_unique[books.isbn,book_id,' . $book_id . ']|min_length[10]|max_length[13]';
        }


        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed. Please check the inputs.',
                'errors' => \Config\Services::validation()->getErrors(),
            ]);
        }


        $data = [
            'title' => $title,
            'isbn' => $isbn,
            'author' => $this->request->getPost('author'),
            'published_date' => $this->request->getPost('published_date'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'), // Optional field
            'category_id' => $this->request->getPost('category'), // Update the category_id if provided
            'quantity' => $this->request->getPost('quantity'),
        ];


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


            $uploadPath = FCPATH . 'uploads/books/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }


            $newFileName = $file->getRandomName();
            if (!$file->move($uploadPath, $newFileName)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to upload the photo.',
                ]);
            }


            $data['photo'] = $newFileName;


            if ($existingBookData && !empty($existingBookData['photo'])) {
                @unlink($uploadPath . $existingBookData['photo']);
            }
        }


        if ($bookModel->update($book_id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Book updated successfully.',
            ]);
        } else {

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


        $books = $bookModel->select('books.*, categories.name as category_name')
            ->join('categories', 'books.category_id = categories.category_id', 'left')
            ->findAll();

        return view('admin/view_books', ['books' => $books]);
    }





    public function deleteBook($book_id)
    {
        $bookModel = new BookModel();


        if (!$bookModel->find($book_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Book not found'
            ]);
        }


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
