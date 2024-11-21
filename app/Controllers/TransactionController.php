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


    $existingTransaction = $this->transactionModel
        ->where('user_id', $user_id)
        ->where('book_id', $book_id)
        ->where('status', 'pending')
        ->first();

    if ($existingTransaction) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'You already have a pending request for this book.']);
    }


    $book = $this->bookModel->find($book_id);
    if (!$book || $book['status'] != 'available') {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Book is not available.']);
    }


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


    $return_date = date('Y-m-d');
    $due_date = $transaction['due_date'];


    $remarks = (strtotime($return_date) > strtotime($due_date)) ? 'Late' : 'On time';


    $this->transactionModel->update($transaction_id, [
        'return_date' => $return_date,
        'status' => 'returned',
        'remarks' => $remarks
    ]);


    $book = $this->bookModel->find($transaction['book_id']);
    if ($book) {

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


    $user_id = $this->request->getVar('user_id');
    $transactions = $this->transactionModel->where('user_id', $user_id)
                                           ->where('status', 'borrowed')
                                           ->findAll();

    if (empty($transactions)) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'You have no borrowed books.']);
    }


    $db = \Config\Database::connect();
    $db->transStart();

    foreach ($transactions as $transaction) {

        $return_date = date('Y-m-d');
        $due_date = $transaction['due_date'];


        $remarks = (strtotime($return_date) > strtotime($due_date)) ? 'Late' : 'On time';


        $this->transactionModel->update($transaction['transaction_id'], [
            'return_date' => $return_date,
            'status' => 'returned',
            'remarks' => $remarks
        ]);


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


        $data['borrowed'] = [];
        foreach ($transactions as $transaction) {
            $book = $this->bookModel->find($transaction['book_id']);
            $data['borrowed'][] = [
                'transaction_id' => $transaction['transaction_id'],
                'title' => $book['title'],
                'borrow_date' => $transaction['borrow_date']
            ];
        }


        $data['books'] = $this->bookModel->where('status', 'available')->findAll();

        echo view('student/dashboard', $data);
    }
}