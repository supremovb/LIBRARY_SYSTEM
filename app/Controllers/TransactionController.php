<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\BookModel;
use CodeIgniter\Controller;

class TransactionController extends BaseController
{
    protected $transactionModel;
    protected $bookModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->transactionModel = new TransactionModel();
        $this->bookModel = new BookModel();
    }

    public function borrow()
{
    $session = session();

    if (!$session->get('logged_in')) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'You must be logged in.']);
    }

    if ($session->get('role') != 'student') {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Only students can borrow books.']);
    }

    $book_id = $this->request->getVar('book_id');
    $user_id = $session->get('user_id');

    // Force fresh query to check for pending transactions
    $existingTransaction = $this->transactionModel
        ->where('user_id', $user_id)
        ->where('book_id', $book_id)
        ->where('status', 'pending')
        ->first();

    if ($existingTransaction) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'You already have a pending request for this book.']);
    }

    // Check if the book exists and is available
    $book = $this->bookModel->find($book_id);
    if (!$book || $book['status'] != 'available') {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Book is not available.']);
    }

    // Create a transaction with status 'pending' (do not decrease book quantity here)
    $data = [
        'book_id' => $book_id,
        'user_id' => $user_id,
        'borrow_date' => date('Y-m-d'),
        'status' => 'pending', // Set to pending
    ];

    if ($this->transactionModel->insert($data)) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Borrow request submitted successfully.']);
    }

    return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to submit borrow request.']);
}





public function createTransaction()
{
    $transactionData = [
        'user_id' => $this->request->getPost('user_id'),
        'book_id' => $this->request->getPost('book_id'),
        'borrow_date' => $this->request->getPost('borrow_date'),
        'return_date' => $this->request->getPost('return_date'),
        'due_date' => $this->request->getPost('due_date'),  // Include the due_date
        'status' => 'pending', // Example status
    ];

    $transactionModel = new \App\Models\TransactionModel();
    $transactionModel->save($transactionData);

    return redirect()->to('/transactions');
}

    


public function returnBook()
{
    $session = session();
    if (!$session->get('logged_in') || $session->get('role') != 'student') {
        return redirect()->to('/');
    }

    $transaction_id = $this->request->getVar('transaction_id');
    $transaction = $this->transactionModel->find($transaction_id);

    if (!$transaction || $transaction['status'] != 'borrowed') {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid transaction.']);
    }

    // Get the current date (return date) and due date for comparison
    $return_date = date('Y-m-d');
    $due_date = $transaction['due_date'];

    // Determine if the return is late or on time
    $remarks = (strtotime($return_date) > strtotime($due_date)) ? 'Late' : 'On time';

    // Update transaction with return date, status, and remarks
    $this->transactionModel->update($transaction_id, [
        'return_date' => $return_date,
        'status' => 'returned',
        'remarks' => $remarks
    ]);

    // Fetch the book details
    $book = $this->bookModel->find($transaction['book_id']);
    if ($book) {
        // Increment the book quantity by 1
        $this->bookModel->update($transaction['book_id'], [
            'quantity' => $book['quantity'] + 1
        ]);
    }

    return $this->response->setJSON(['status' => 'success', 'message' => 'Book returned successfully.']);
}

public function returnAllBooks()
{
    $session = session();
    if (!$session->get('logged_in') || $session->get('role') != 'student') {
        return $this->response->setJSON(['status' => 'error', 'message' => 'You must be logged in as a student.']);
    }

    // Fetch all borrowed books for the logged-in user
    $user_id = $this->request->getVar('user_id');
    $transactions = $this->transactionModel->where('user_id', $user_id)
                                           ->where('status', 'borrowed')
                                           ->findAll();

    if (empty($transactions)) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'You have no borrowed books.']);
    }

    // Begin transaction for returning all books
    $db = \Config\Database::connect();
    $db->transStart();

    foreach ($transactions as $transaction) {
        // Get the current date (return date) and due date for comparison
        $return_date = date('Y-m-d');
        $due_date = $transaction['due_date'];

        // Determine if the return is late or on time
        $remarks = (strtotime($return_date) > strtotime($due_date)) ? 'Late' : 'On time';

        // Update the transaction with return date, status, and remarks
        $this->transactionModel->update($transaction['transaction_id'], [
            'return_date' => $return_date,
            'status' => 'returned',
            'remarks' => $remarks
        ]);

        // Fetch the book details and update the quantity
        $book = $this->bookModel->find($transaction['book_id']);
        if ($book) {
            $this->bookModel->update($transaction['book_id'], [
                'quantity' => $book['quantity'] + 1
            ]);
        }
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to return all books.']);
    } else {
        return $this->response->setJSON(['status' => 'success', 'message' => 'All books returned successfully.']);
    }
}




    public function myBorrowedBooks()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') != 'student') {
            return redirect()->to('/');
        }

        $user_id = $session->get('user_id');
        $transactions = $this->transactionModel->where(['user_id' => $user_id, 'status' => 'borrowed'])->findAll();

        // Fetch book details for each transaction
        $data['borrowed'] = [];
        foreach ($transactions as $transaction) {
            $book = $this->bookModel->find($transaction['book_id']);
            $data['borrowed'][] = [
                'transaction_id' => $transaction['transaction_id'],
                'title' => $book['title'],
                'borrow_date' => $transaction['borrow_date']
            ];
        }

        // Fetch available books
        $data['books'] = $this->bookModel->where('status', 'available')->findAll();

        echo view('student/dashboard', $data);
    }
}