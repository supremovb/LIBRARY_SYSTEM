<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';              // Table name
    protected $primaryKey = 'book_id';       // Primary key column

    protected $allowedFields = [
        'title', 
        'description', 
        'author', 
        'isbn', 
        'published_date', 
        'status', 
        'photo' // Photo field for the book cover
    ]; // Fields allowed for mass assignment

    // Enable automatic timestamps if needed (e.g., for created_at/updated_at fields)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at'; // Field to store the creation timestamp
    protected $updatedField  = 'updated_at'; // Field to store the update timestamp
    // protected $deletedField  = 'deleted_at'; // Soft delete field (optional)

    // Validation rules for data
    protected $validationRules = [
        'title'          => 'required|min_length[3]|max_length[255]',
        'description'    => 'permit_empty|max_length[2000]', // Validation for description
        'author'         => 'required|min_length[3]|max_length[255]',
        'isbn'           => 'required|min_length[10]|max_length[13]',
        'published_date' => 'required|valid_date',
        'status'         => 'in_list[available,borrowed,pending]',
        'photo'          => 'permit_empty|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]'
    ];

    // Validation messages for errors
    protected $validationMessages = [
        'title' => [
            'required'   => 'The book title is required.',
            'min_length' => 'The book title must be at least 3 characters long.',
            'max_length' => 'The book title cannot exceed 255 characters.',
        ],
        'description' => [
            'max_length' => 'The description cannot exceed 2000 characters.',
        ],
        'author' => [
            'required'   => 'The author name is required.',
            'min_length' => 'The author name must be at least 3 characters long.',
            'max_length' => 'The author name cannot exceed 255 characters.',
        ],
        'isbn' => [
            'required'   => 'The ISBN is required.',
            'is_unique'  => 'The ISBN must be unique.',
            'min_length' => 'The ISBN must be at least 10 characters long.',
            'max_length' => 'The ISBN cannot exceed 13 characters.',
        ],
        'published_date' => [
            'required'   => 'The published date is required.',
            'valid_date' => 'The published date must be a valid date.',
        ],
        'status' => [
            'in_list'    => 'The status must be one of: available, borrowed, or reserved.',
        ],
        'photo' => [
            'mime_in'  => 'The uploaded photo must be a JPG, JPEG, or PNG image.',
            'max_size' => 'The uploaded photo cannot exceed 2MB in size.',
        ],
    ];

    protected $skipValidation = false; // Validation will always run before saving/updating data
}
