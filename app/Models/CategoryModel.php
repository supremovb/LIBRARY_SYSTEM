<?php

// app/Models/CategoryModel.php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';    // Table name
    protected $primaryKey = 'category_id';  // Primary key
    protected $allowedFields = ['name', 'description', 'created_at', 'updated_at']; // Fields that can be inserted/updated
    protected $useTimestamps = true;    // Enable automatic timestamps for created_at and updated_at
    protected $createdField  = 'created_at';  // Name of the column for created timestamp
    protected $updatedField  = 'updated_at';  // Name of the column for updated timestamp

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty|max_length[255]'
    ];

    // Custom Validation Messages
    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 3 characters long',
            'max_length' => 'Category name can\'t be longer than 255 characters'
        ],
        'description' => [
            'max_length' => 'Description can\'t be longer than 255 characters'
        ]
    ];

    // Return a list of categories for selection (used for dropdown or listing)
    public function getCategories()
    {
        return $this->findAll();
    }
}
