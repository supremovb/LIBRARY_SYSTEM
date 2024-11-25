<?php



namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';    
    protected $primaryKey = 'category_id';  
    protected $allowedFields = ['name', 'description', 'created_at', 'updated_at']; 
    protected $useTimestamps = true;    
    protected $createdField  = 'created_at';  
    protected $updatedField  = 'updated_at';  

    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty|max_length[255]'
    ];

    
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

    
    public function getCategories()
    {
        return $this->findAll();
    }
}
