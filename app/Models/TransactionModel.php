<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\UserModel;  // Add UserModel
use App\Models\BookModel;  // Add BookModel

class TransactionModel extends Model
{
    protected $table = 'transactions';  // Table name
    protected $primaryKey = 'transaction_id'; // Primary key column

    protected $allowedFields = [
        'book_id',
        'user_id',
        'borrow_date',
        'return_date',
        'due_date',
        'remarks', // Including due_date in allowed fields
        'status'
    ];

    // Enable automatic timestamps for creation and updates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation rules for data integrity
    protected $validationRules = [
        'book_id'     => 'required|is_natural_no_zero',
        'user_id'     => 'required|is_natural_no_zero',
        'borrow_date' => 'required|valid_date',
        'return_date' => 'permit_empty|valid_date',
        'due_date'    => 'permit_empty|valid_date',
        'remarks'     => 'permit_empty|string',  // Validate remarks (optional)
        'status'      => 'required|in_list[borrowed,returned,pending,rejected]'
    ];

    public function getPendingTransactions($user_id = null)
{
    $builder = $this->db->table('transactions')
        ->select([
            'transactions.transaction_id',
            'transactions.user_id',
            'transactions.book_id',
            'transactions.borrow_date',
            'transactions.due_date',
            'transactions.status',
            'COALESCE(users.firstname, "Unknown") as firstname',
            'COALESCE(users.lastname, "") as lastname',
            'COALESCE(books.title, "No Title") as title'
        ])
        ->join('users', 'users.user_id = transactions.user_id', 'left')  // Make sure user_id is correct
        ->join('books', 'books.book_id = transactions.book_id', 'left')  // Ensure book_id is correct
        ->where('transactions.status', 'pending');

    if ($user_id !== null) {
        $builder->where('transactions.user_id', $user_id);
    }

    log_message('debug', 'SQL Query: ' . $builder->getCompiledSelect());

    return $builder->get()->getResultArray();
}

public function showPendingTransactions($user_id = null)
{
    // Get the pending transactions from the model
    $pendingTransactions = $this->getPendingTransactions($user_id);

    // Debug: Log the query result for pending transactions
    if (empty($pendingTransactions)) {
        log_message('debug', 'No pending transactions found.');
    } else {
        log_message('debug', 'Pending Transactions: ' . print_r($pendingTransactions, true));
    }

    // Pass the transactions to the view
    return view('admin/approve_reject_transactions', ['pendingTransactions' => $pendingTransactions]);
}

    


    // Additional method to fetch overdue transactions based on current date and due date
    public function getOverdueTransactions()
    {
        return $this->where('due_date <', date('Y-m-d'))
                    ->where('status !=', 'returned')
                    ->findAll();
    }
}