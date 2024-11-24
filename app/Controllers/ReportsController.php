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
        // Fetch all books from the database
        $booksModel = model('App\Models\BookModel');
        $books = $booksModel->findAll();  // Fetch all books

        // Load the view with the books data
        $htmlContent = view('admin/reports/book_report', ['books' => $books]);

        // Generate PDF from the HTML content with dynamic filename
        $this->generatePdf($htmlContent, "book_report.pdf");
    }

    public function generateUserReport()
    {
        // Fetch all users from the database
        $usersModel = new UserModel(); // Initialize the model here
        $users = $usersModel->findAll();  // Fetch all users

        // Check if data is returned correctly
        if (!$users) {
            return 'No users found.';
        }

        // Load the view with the users data
        $htmlContent = view('admin/reports/user_report', ['users' => $users]);

        // Generate PDF from the HTML content with dynamic filename
        $this->generatePdf($htmlContent, "user_report.pdf");
    }

    public function generateTransactionReport()
    {
        // Fetch all transactions from the database
        $transactionsModel = new TransactionModel();  // Initialize the Transactions model
        $transactions = $transactionsModel->findAll();  // Fetch all transactions

        // Fetch book titles and user names for each transaction
        $booksModel = new BookModel();  // Initialize the Books model
        $usersModel = new UserModel();  // Initialize the Users model

        // Check if data is returned correctly
        if (!$transactions) {
            return 'No transactions found.';
        }

        // Add book titles and user names to each transaction
        foreach ($transactions as &$transaction) {
            $book = $booksModel->find($transaction['book_id']);
            $user = $usersModel->find($transaction['user_id']);
            $transaction['book_title'] = $book['title'];  // Add book title to transaction
            $transaction['user_name'] = $user['firstname'] . ' ' . $user['lastname'];  // Add user name to transaction
        }

        // Load the view with the transactions data
        $htmlContent = view('admin/reports/transaction_report', ['transactions' => $transactions]);

        // Generate PDF from the HTML content with dynamic filename
        $this->generatePdf($htmlContent, "transaction_report.pdf");
    }

    private function generatePdf($htmlContent, $fileName)
    {
        // Initialize DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);

        // Set paper size (A4)
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF (first pass to load content)
        $dompdf->render();

        // Output the generated PDF (force download)
        $dompdf->stream($fileName, array("Attachment" => 1));
    }
}
