<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';              
    protected $primaryKey = 'book_id';       

    protected $allowedFields = [
        'title',
        'description',
        'author',
        'isbn',
        'published_date',
        'status',
        'photo',
        'category_id',
        'quantity' 
    ]; 

    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at'; 
    

    
    protected $validationRules = [
        'title'          => 'required|min_length[3]|max_length[255]',
        'description'    => 'permit_empty|max_length[2000]', 
        'author'         => 'required|min_length[3]|max_length[255]',
        'isbn'           => 'required|min_length[10]|max_length[13]',
        'published_date' => 'required|valid_date',
        'status'         => 'in_list[available,borrowed,pending]',
        'photo'          => 'permit_empty|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]',
        'category_id'    => 'required|is_not_unique[categories.category_id]',
        'quantity'       => 'required|integer|min_length[1]' 
    ];

    
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
        'quantity' => [
            'required' => 'The quantity of books is required.',
            'integer'  => 'The quantity must be a valid number.',
            'min_length' => 'The quantity must be at least 1.',
        ],
    ];

    protected $skipValidation = false; 

    
    public function get_books_by_category($category_id)
    {
        return $this->where('category_id', $category_id)->findAll();
    }

    

    public function getBooksByCategories($categories, $userId)
    {
        $builder = $this->db->table('books')
            ->select('books.*, AVG(book_review.rating) as avg_rating')
            ->join('book_review', 'books.book_id = book_review.book_id', 'left') 
            ->groupBy('books.book_id');  

        
        if (!empty($categories)) {
            $builder->whereIn('books.category_id', array_column($categories, 'category_id'));
        }

        
        $builder->having('avg_rating >=', 3)
            ->orderBy('avg_rating', 'DESC');  

        
        $query = $builder->get();

        
        if ($query !== false) {
            return $query->getResult();  
        } else {
            
            log_message('error', 'Query failed: ' . $this->db->getLastQuery());
            return [];  
        }
    }

    public function getRelatedBooksByCategories($categories)
    {
        
        if (empty($categories)) {
            return [];
        }

        $builder = $this->db->table('books')
            ->select('books.*')
            ->whereIn('books.category_id', array_column($categories, 'category_id'))
            ->orderBy('books.title', 'ASC'); 

        
        $query = $builder->get();

        
        if ($query !== false) {
            return $query->getResult();  
        } else {
            
            log_message('error', 'Query failed: ' . $this->db->getLastQuery());
            return [];  
        }
    }


    
    public function get_books_by_status($status)
    {
        return $this->where('status', $status)->findAll();
    }

    
    public function getNearDueBooks()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('borrowed_books'); 
        $builder->join('users', 'users.user_id = borrowed_books.user_id');
        $builder->where('borrowed_books.due_date <=', date('Y-m-d', strtotime('+3 days')));
        $builder->where('borrowed_books.due_date >=', date('Y-m-d'));
        return $builder->get()->getResult();
    }

    
    public function getOverdueBooks()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('borrowed_books');
        $builder->join('users', 'users.user_id = borrowed_books.user_id');
        $builder->where('borrowed_books.due_date <', date('Y-m-d'));
        return $builder->get()->getResult();
    }
}
