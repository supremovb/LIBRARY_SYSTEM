<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\UserModel;  
use App\Models\BookModel;  

class TransactionModel extends Model
{
    protected $table = 'transactions';  
    protected $primaryKey = 'transaction_id'; 

    protected $allowedFields = [
        'book_id',
        'user_id',
        'borrow_date',
        'return_date',
        'due_date',
        'remarks', 
        'status'
    ];

    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    
    protected $validationRules = [
        'book_id'     => 'required|is_natural_no_zero',
        'user_id'     => 'required|is_natural_no_zero',
        'borrow_date' => 'required|valid_date',
        'return_date' => 'permit_empty|valid_date',
        'due_date'    => 'permit_empty|valid_date',
        'remarks'     => 'permit_empty|string',  
        'status'      => 'required|in_list[borrowed,returned,pending,rejected]'
    ];

    
    

    public function getUserBorrowedCategories($userId)
    {
        return $this->db->table('transactions')
            ->select('transactions.*, books.*, categories.name')
            ->join('books', 'transactions.book_id = books.book_id')
            ->join('categories', 'books.category_id = categories.category_id')
            ->where('transactions.user_id', $userId)
            ->get()
            ->getResult();
    }


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
            ->join('users', 'users.user_id = transactions.user_id', 'left')  
            ->join('books', 'books.book_id = transactions.book_id', 'left')  
            ->where('transactions.status', 'pending');

        if ($user_id !== null) {
            $builder->where('transactions.user_id', $user_id);
        }

        log_message('debug', 'SQL Query: ' . $builder->getCompiledSelect());

        return $builder->get()->getResultArray();
    }

    public function showPendingTransactions($user_id = null)
    {
        
        $pendingTransactions = $this->getPendingTransactions($user_id);

        
        if (empty($pendingTransactions)) {
            log_message('debug', 'No pending transactions found.');
        } else {
            log_message('debug', 'Pending Transactions: ' . print_r($pendingTransactions, true));
        }

        
        return view('admin/approve_reject_transactions', ['pendingTransactions' => $pendingTransactions]);
    }

    public function getOverdueBooks()
    {
        $overdueBooks = $this->where('return_date', null)
            ->where('due_date <', date('Y-m-d'))
            ->findAll();

        
        log_message('error', 'Overdue books: ' . print_r($overdueBooks, true));

        return $overdueBooks;
    }



    
    public function getOverdueTransactions()
    {
        return $this->where('due_date <', date('Y-m-d'))
            ->where('status !=', 'returned')
            ->findAll();
    }
}
