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

$routes->get('admin/view-users', 'AdminController::viewUsers');

$routes->post('admin/approveAllTransactions', 'AdminController::approveAllTransactions');

$routes->post('admin/rejectAllTransactions', 'AdminController::rejectAllTransactions');

$routes->post('transaction/returnAllBooks', 'TransactionController::returnAllBooks');

$routes->post('admin/edit-role', 'AdminController::editRole');

$routes->get('admin/edit-role', 'AdminController::editRole');

$routes->post('admin/delete-user', 'AdminController::deleteUser');

$routes->post('admin/delete-category/(:num)', 'AdminController::deleteCategory/$1');

$routes->get('/student/notifications', 'NotificationController::index');
$routes->get('/notifications/getUnreadCount', 'NotificationController::getUnreadCount');
$routes->post('/notifications/markAsRead', 'NotificationController::markAsRead');

$routes->get('/admin/notifyNearDueBooks', 'AdminController::notifyNearDueBooks');
$routes->get('/admin/notifyOverdueBooks', 'AdminController::notifyOverdueBooks');

$routes->get('notification/fetch-notifications', 'NotificationController::fetchNotifications');
$routes->get('notification/unread-count', 'NotificationController::getUnreadCount');



$routes->get('admin/borrowed-books', 'AdminController::borrowedBooks');


$routes->post('admin/send-notification-to-overdue', 'NotificationController::sendNotificationToOverdue');

$routes->get('admin/generate-book-report', 'ReportsController::generateBookReport');
$routes->get('admin/generate-user-report', 'ReportsController::generateUserReport');
$routes->get('admin/generate-transaction-report', 'ReportsController::generateTransactionReport');

// In app/config/Routes.php
$routes->post('user/submit-review', 'UserController::submitReview');


$routes->get('student/book-reviews', 'BookReviewController::index');

$routes->post('book_review/delete/(:num)', 'BookReviewController::delete/$1');

$routes->post('book_review/update', 'BookReviewController::update');

$routes->post('admin/rejectAllTransactions', 'AdminController::rejectAllTransactions');

$routes->get('admin/rejectAllTransactions', 'AdminController::rejectAllTransactions');

// app/Config/Routes.php
$routes->get('admin/undefined', 'AdminController::rejectAllTransactions');

// In your Routes file
$routes->get('student/view-history', 'TransactionController::view_history');


// librarian

$routes->get('librarian/dashboard', 'LibrarianController::dashboard');
$routes->get('librarian/dashboard', 'LibrarianController::dashboard');
$routes->get('librarian/add-book', 'LibrarianController::addBook');
$routes->get('librarian/update-book/(:num)', 'LibrarianController::updateBook/$1');
$routes->get('librarian/delete-book/(:num)', 'LibrarianController::deleteBook/$1');
$routes->get('librarian/manage-students', 'LibrarianController::manageStudents');

$routes->get('/librarian/dashboard', 'LibrarianController::dashboard');   // Admin dashboard
$routes->get('/librarian/add-book', 'LibrarianController::addBook');      // Show form to add a book
$routes->post('/librarian/store-book', 'LibrarianController::createBook');  // Store the new book
$routes->get('/librarian/edit-book/(:num)', 'LibrarianController::editBook/$1');  // Edit book form
$routes->post('/librarian/update-book', 'LibrarianController::updateBookDetails');  // Update book details
$routes->get('/librarian/delete-book/(:num)', 'LibrarianController::deleteBook/$1');  // Delete book

$routes->post('librarian/create-book', 'LibrarianController::createBook');
$routes->get('librarian/add-book', 'LibrarianController::addBook');
$routes->get('/librarian/create-book', 'LibrarianController::addBook');

$routes->get('/librarian/dashboard', 'LibrarianController::dashboard');
$routes->get('librarian/dashboard', 'LibrarianController::dashboard');
$routes->get('/librarian/add-book', 'LibrarianController::addBook');
$routes->post('/librarian/create-book', 'LibrarianController::createBook');
$routes->get('/librarian/edit-book/(:num)', 'LibrarianController::editBook/$1');
$routes->post('/librarian/update-book', 'LibrarianController::updateBookDetails');
$routes->delete('/librarian/delete-book/(:num)', 'LibrarianController::deleteBook/$1');
$routes->get('librarian/edit-book/(:num)', 'LibrarianController::editBook/$1');
$routes->post('librarian/update-book', 'LibrarianController::updateBookDetails');

$routes->post('/librarian/delete-book/(:num)', 'LibrarianController::deleteBook/$1');

$routes->delete('librarian/delete-book/(:num)', 'LibrarianController::deleteBook/$1');

$routes->get('librarian/view-books', 'LibrarianController::viewBooks');
$routes->get('/librarian/view-books', 'LibrarianController::viewBooks');

$routes->get('librarian/view_books', 'LibrarianController::viewBooks');
$routes->get('/librarian/view_books', 'LibrarianController::viewBooks');

$routes->get('librarian/approve_reject_transactions', 'LibrarianController::approve_reject_transactions');

$routes->get('librarian/edit-book/(:num)', 'LibrarianController::editBook/$1');  // Route for editing book

$routes->get('librarian/create-book', 'LibrarianController::addBook');  // This should load the Add Book form
$routes->post('librarian/create-book', 'LibrarianController::createBook'); // This should handle form submission

$routes->post('librarian/approveTransaction/(:num)', 'LibrarianController::approveTransaction/$1');

$routes->get('librarian/rejectTransaction/(:num)', 'LibrarianController::rejectTransaction/$1');

$routes->get('librarian/view-profile', 'LibrarianController::viewProfile');
$routes->post('librarian/update-profile', 'LibrarianController::updateProfile');

$routes->get('librarian_dashboard/add-category', 'LibrarianController::add_Category');

// Route for handling the form submission (POST request)
$routes->post('librarian/add-category', 'LibrarianController::addCategory');


$routes->get('librarian/add-category', 'LibrarianController::add_Category');
$routes->post('librarian/add-category', 'LibrarianController::addCategory');

$routes->get('librarian_dashboard', 'LibrarianController::dashboard');


$routes->get('librarian/categories', 'LibrarianController::categories');

$routes->get('librarian/delete-category/(:num)', 'LibrarianController::deleteCategory/$1');

$routes->get('librarian/edit-category/(:num)', 'LibrarianController::editCategory/$1');

$routes->post('librarian/update-category', 'LibrarianController::updateCategory');


$routes->get('librarian/view-users', 'LibrarianController::viewUsers');

$routes->post('librarian/approveAllTransactions', 'LibrarianController::approveAllTransactions');

$routes->post('librarian/rejectAllTransactions', 'LibrarianController::rejectAllTransactions');

$routes->post('librarian/edit-role', 'LibrarianController::editRole');

$routes->get('librarian/edit-role', 'LibrarianController::editRole');

$routes->post('librarian/delete-user', 'LibrarianController::deleteUser');

$routes->post('librarian/delete-category/(:num)', 'LibrarianController::deleteCategory/$1');

$routes->get('/librarian/notifyNearDueBooks', 'LibrarianController::notifyNearDueBooks');
$routes->get('/librarian/notifyOverdueBooks', 'LibrarianController::notifyOverdueBooks');

$routes->get('librarian/borrowed-books', 'LibrarianController::borrowedBooks');


$routes->post('librarian/send-notification-to-overdue', 'NotificationController::sendNotificationToOverdue');

$routes->get('librarian/generate-book-report', 'ReportsController::generateBookReport');
$routes->get('librarian/generate-user-report', 'ReportsController::generateUserReport');
$routes->get('librarian/generate-transaction-report', 'ReportsController::generateTransactionReport');

$routes->post('librarian/rejectAllTransactions', 'LibrarianController::rejectAllTransactions');

$routes->get('librarian/rejectAllTransactions', 'LibrarianController::rejectAllTransactions');

// app/Config/Routes.php
$routes->get('librarian/undefined', 'LibrarianController::rejectAllTransactions');

$routes->get('/librarian/user/logout', 'LibrarianController::logout');

$routes->get('/dashboard', 'LibrarianController::dashboard');

$routes->get('librarian/dashboard', 'LibrarianController::dashboard');

$routes->get('librarian/logout', 'LibrarianController::logout');

$routes->get('/librarian/dashboard', 'LibrarianController::dashboard');

$routes->get('/librarian_dashboard', 'LibrarianController::dashboard');

$routes->get('/librarian_dashboard/edit-book/(:num)', 'LibrarianController::editBook/$1');
$routes->post('/librarian_dashboard/update-book', 'LibrarianController::updateBookDetails');
$routes->post('librarian_dashboard/update-book', 'LibrarianController::updateBookDetails');
$routes->get('/librarian_dashboard/edit-book/(:num)', 'LibrarianController::editBook/$1');  // Edit book form
$routes->post('/librarian_dashboard/update-book', 'LibrarianController::updateBookDetails');  // Update book details
$routes->get('librarian_dashboard/edit-book/(:num)', 'LibrarianController::editBook/$1');

$routes->get('librarian/librarian_dashboard', 'LibrarianController::dashboard');

$routes->get('librarian/librarian_dashboard/edit-book/(:num)', 'LibrarianController::editBook/$1');

// Route to handle the book deletion with the book ID
$routes->post('librarian/librarian/delete-book/(:num)', 'LibrarianController::deleteBook/$1');

$routes->get('NotificationController/updateNotifications', 'NotificationController::updateNotifications');
$routes->get('NotificationController/unreadCount', 'NotificationController::unreadCount');

$routes->get('NotificationController/fetchNotifications', 'NotificationController::fetchNotifications');
$routes->post('NotificationController/markAsRead', 'NotificationController::markAsRead');


$routes->get('student/get_recommendations/(:num)', 'UserController::get_recommendations/$1');





$routes->get('/admin/user/logout', 'AdminController::logout');
