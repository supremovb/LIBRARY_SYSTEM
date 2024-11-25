<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\TransactionModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportsController extends BaseController
{
    public function generateBookReport()
    {
        
        $booksModel = model('App\Models\BookModel');
        $books = $booksModel->findAll();  

        
        $htmlContent = view('admin/reports/book_report', ['books' => $books]);

        
        $this->generatePdf($htmlContent, "book_report.pdf");
    }

    public function generateUserReport()
    {
        
        $usersModel = new UserModel(); 
        $users = $usersModel->findAll();  

        
        if (!$users) {
            return 'No users found.';
        }

        
        $htmlContent = view('admin/reports/user_report', ['users' => $users]);

        
        $this->generatePdf($htmlContent, "user_report.pdf");
    }

    public function generateTransactionReport()
    {
        
        $transactionsModel = new TransactionModel();  
        $transactions = $transactionsModel->findAll();  

        
        $booksModel = new BookModel();  
        $usersModel = new UserModel();  

        
        if (!$transactions) {
            return 'No transactions found.';
        }

        
        foreach ($transactions as &$transaction) {
            $book = $booksModel->find($transaction['book_id']);
            $user = $usersModel->find($transaction['user_id']);
            $transaction['book_title'] = $book['title'];  
            $transaction['user_name'] = $user['firstname'] . ' ' . $user['lastname'];  
        }

        
        $htmlContent = view('admin/reports/transaction_report', ['transactions' => $transactions]);

        
        $this->generatePdf($htmlContent, "transaction_report.pdf");
    }

    private function generatePdf($htmlContent, $fileName)
    {
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);

        
        $dompdf->setPaper('A4', 'portrait');

        
        $dompdf->render();

        
        $dompdf->stream($fileName, array("Attachment" => 1));
    }
}
