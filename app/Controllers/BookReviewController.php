<?php

namespace App\Controllers;

use App\Models\BookReviewModel;
use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\BookReview;

class BookReviewController extends BaseController
{
    public function index()
    {
        $bookReviewModel = new BookReview();
        $userModel = new UserModel();
        $bookModel = new BookModel();

        
        $reviews = $bookReviewModel
            ->select('book_review.*, users.firstname, users.lastname, books.title')
            ->join('users', 'book_review.user_id = users.user_id')
            ->join('books', 'book_review.book_id = books.book_id')
            ->findAll();

        
        if ($reviews === false) {
            
            log_message('error', 'Failed to fetch reviews: ' . $bookReviewModel->errors());
            
            $reviews = [];
        }

        
        return view('student/book_reviews', ['reviews' => $reviews]);
    }

    public function edit($reviewId)
    {
        
        $bookReviewModel = new BookReview();
        $userModel = new UserModel();
        $bookModel = new BookModel();

        
        $review = $bookReviewModel->find($reviewId);

        if (!$review) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Review not found");
        }

        
        return view('student/edit_book_review', ['review' => $review]);
    }

    public function update()
    {
        $bookReviewModel = new BookReview();

        
        if ($this->validate([
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'review' => 'required|string'
        ])) {
            
            $updatedData = [
                'rating' => $this->request->getPost('rating'),
                'review' => $this->request->getPost('review'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            
            $reviewId = $this->request->getPost('review_id');
            $bookReviewModel->update($reviewId, $updatedData);

            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Review updated successfully.'
            ]);
        } else {
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed. Please check your input.'
            ]);
        }
    }




    public function delete($reviewId)
    {
        $bookReviewModel = new BookReview();

        
        if ($bookReviewModel->delete($reviewId)) {
            return 'success';
        } else {
            return 'error';
        }
    }
}
