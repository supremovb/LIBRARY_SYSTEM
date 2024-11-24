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

        // Fetch reviews with necessary joins
        $reviews = $bookReviewModel
            ->select('book_review.*, users.firstname, users.lastname, books.title')
            ->join('users', 'book_review.user_id = users.user_id')
            ->join('books', 'book_review.book_id = books.book_id')
            ->findAll();

        // Check for errors and handle the response
        if ($reviews === false) {
            // Log the error for debugging
            log_message('error', 'Failed to fetch reviews: ' . $bookReviewModel->errors());
            // Handle the failure, show a message, or return an empty array
            $reviews = [];
        }

        // Return to the view with reviews data
        return view('student/book_reviews', ['reviews' => $reviews]);
    }

    public function edit($reviewId)
    {
        // Load the models
        $bookReviewModel = new BookReview();
        $userModel = new UserModel();
        $bookModel = new BookModel();

        // Fetch the review data
        $review = $bookReviewModel->find($reviewId);

        if (!$review) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Review not found");
        }

        // Pass the review data to the view
        return view('student/edit_book_review', ['review' => $review]);
    }

    public function update()
    {
        $bookReviewModel = new BookReview();

        // Validate the form input
        if ($this->validate([
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'review' => 'required|string'
        ])) {
            // Prepare the updated data
            $updatedData = [
                'rating' => $this->request->getPost('rating'),
                'review' => $this->request->getPost('review'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Update the review in the database
            $reviewId = $this->request->getPost('review_id');
            $bookReviewModel->update($reviewId, $updatedData);

            // Send JSON response indicating success
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Review updated successfully.'
            ]);
        } else {
            // If validation fails, return validation errors
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed. Please check your input.'
            ]);
        }
    }




    public function delete($reviewId)
    {
        $bookReviewModel = new BookReview();

        // Check if the review exists and delete it
        if ($bookReviewModel->delete($reviewId)) {
            return 'success';
        } else {
            return 'error';
        }
    }
}
