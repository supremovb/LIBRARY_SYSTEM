<?php

namespace App\Models;

use CodeIgniter\Model;

class BookReview extends Model
{
    protected $table = 'book_review';
    protected $primaryKey = 'review_id';
    protected $allowedFields = ['book_id', 'user_id', 'rating', 'review', 'created_at', 'updated_at'];

    // Create a new review
    public function createReview($bookId, $userId, $rating, $reviewText)
    {
        return $this->insert([
            'book_id' => $bookId,
            'user_id' => $userId,
            'rating' => $rating,
            'review' => $reviewText,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')  // Set updated_at when creating a new review
        ]);
    }

    // Update an existing review
    public function updateReview($reviewId, $rating, $reviewText)
    {
        return $this->update($reviewId, [
            'rating' => $rating,
            'review' => $reviewText,
            'updated_at' => date('Y-m-d H:i:s')  // Update updated_at when modifying a review
        ]);
    }
}
