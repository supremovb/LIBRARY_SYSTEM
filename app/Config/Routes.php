<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UserController::login');
$routes->post('user/authenticate', 'UserController::authenticate');
$routes->get('/dashboard', 'UserController::dashboard');
$routes->get('user/dashboard', 'UserController::dashboard');
$routes->get('user/logout', 'UserController::logout');
$routes->post('user/register', 'UserController::createStudent');
$routes->get('user/login', 'UserController::login');
$routes->get('user/register', 'UserController::register');
$routes->post('user/createStudent', 'UserController::createStudent');
$routes->get('login', 'UserController::login');
$routes->get('/register', 'UserController::register');
$routes->post('/user/createStudent', 'UserController::createStudent');

$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('admin/add-book', 'AdminController::addBook');
$routes->get('admin/update-book/(:num)', 'AdminController::updateBook/$1');
$routes->get('admin/delete-book/(:num)', 'AdminController::deleteBook/$1');
$routes->get('admin/manage-students', 'AdminController::manageStudents');

// Routes for the Admin
$routes->get('/admin/dashboard', 'AdminController::dashboard');   // Admin dashboard
$routes->get('/admin/add-book', 'AdminController::addBook');      // Show form to add a book
$routes->post('/admin/store-book', 'AdminController::createBook');  // Store the new book
$routes->get('/admin/edit-book/(:num)', 'AdminController::editBook/$1');  // Edit book form
$routes->post('/admin/update-book', 'AdminController::updateBookDetails');  // Update book details
$routes->get('/admin/delete-book/(:num)', 'AdminController::deleteBook/$1');  // Delete book



$routes->post('admin/create-book', 'AdminController::createBook');
$routes->get('admin/add-book', 'AdminController::addBook');
$routes->get('/admin/create-book', 'AdminController::addBook');



// Inside app/Config/Routes.php

$routes->get('/admin/dashboard', 'AdminController::dashboard');
$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('/admin/add-book', 'AdminController::addBook');
$routes->post('/admin/create-book', 'AdminController::createBook');
$routes->get('/admin/edit-book/(:num)', 'AdminController::editBook/$1');
$routes->post('/admin/update-book', 'AdminController::updateBookDetails');
$routes->delete('/admin/delete-book/(:num)', 'AdminController::deleteBook/$1');
$routes->get('admin/edit-book/(:num)', 'AdminController::editBook/$1');
$routes->post('admin/update-book', 'AdminController::updateBookDetails');

$routes->post('/admin/delete-book/(:num)', 'AdminController::deleteBook/$1');

$routes->delete('admin/delete-book/(:num)', 'AdminController::deleteBook/$1');

$routes->post('transaction/borrow', 'TransactionController::borrow');

$routes->post('/transaction/borrow', 'TransactionController::borrow');

$routes->get('transaction/borrow', 'TransactionController::borrow');

$routes->get('/transaction/borrow', 'TransactionController::borrow');


$routes->post('transaction/returnBook', 'TransactionController::returnBook');

$routes->post('/transaction/returnBook', 'TransactionController::returnBook');

$routes->get('admin/view-books', 'AdminController::viewBooks');
$routes->get('/admin/view-books', 'AdminController::viewBooks');
$routes->get('/view-books', 'AdminController::viewBooks');
$routes->get('view-books', 'AdminController::viewBooks');

$routes->get('admin/view_books', 'AdminController::viewBooks');
$routes->get('/admin/view_books', 'AdminController::viewBooks');
$routes->get('/view_books', 'AdminController::viewBooks');
$routes->get('view_books', 'AdminController::viewBooks');

$routes->get('student/my-borrowed-books', 'UserController::myBorrowedBooks');

$routes->get('user/book_history/(:num)', 'UserController::getBookHistory/$1');

$routes->get('student/get_book_details', 'UserController::getBookDetails');

$routes->get('admin/approve_reject_transactions', 'AdminController::approve_reject_transactions');

$routes->get('admin/edit-book/(:num)', 'AdminController::editBook/$1');  // Route for editing book
$routes->get('create-book', 'AdminController::createBook');         // Route for creating book


$routes->get('admin/create-book', 'AdminController::addBook');  // This should load the Add Book form
$routes->post('admin/create-book', 'AdminController::createBook'); // This should handle form submission

$routes->post('admin/approveTransaction/(:num)', 'AdminController::approveTransaction/$1');

$routes->get('admin/rejectTransaction/(:num)', 'AdminController::rejectTransaction/$1');

$routes->get('student/view-profile', 'UserController::viewProfile');
$routes->post('student/update-profile', 'UserController::updateProfile');

$routes->get('admin/view-profile', 'AdminController::viewProfile');
$routes->post('admin/update-profile', 'AdminController::updateProfile');

$routes->get('/forgot-password', 'UserController::forgotPassword');
$routes->post('/send-reset-link', 'UserController::sendResetLink');
$routes->get('/reset-password', 'UserController::resetPassword'); // e.g., /reset-password?token=abc123
$routes->post('/update-password', 'UserController::updatePassword');



$routes->get('/about-us', 'AboutController::aboutUs');

$routes->get('/verify-otp', 'UserController::verifyOtp'); // View OTP input form
$routes->post('/verify-otp', 'UserController::processOtp'); // Handle OTP submission

$routes->get('verify-email', 'UserController::verifyEmail');

// Route for displaying the add category form (GET request)
$routes->get('admin/add-category', 'AdminController::add_Category');

// Route for handling the form submission (POST request)
$routes->post('admin/add-category', 'AdminController::addCategory');


$routes->get('admin/add-category', 'AdminController::add_Category');
$routes->post('admin/add-category', 'AdminController::addCategory');

$routes->get('admin/categories', 'AdminController::categories');

$routes->get('admin/delete-category/(:num)', 'AdminController::deleteCategory/$1');

$routes->get('admin/edit-category/(:num)', 'AdminController::editCategory/$1');

$routes->post('admin/update-category', 'AdminController::updateCategory');

$routes->post('student/borrow_book', 'TransactionController::borrow');



$routes->get('/admin/user/logout', 'AdminController::logout');

















