<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';           
    protected $primaryKey = 'user_id';    

    protected $allowedFields = [
        'username',
        'password', 
        'email',  
        'role', 
        'photo', 
        'firstname',  
        'lastname',   
        'course',     
        'year',       
        'created_at'  
    ]; 

    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at'; 

    
    protected $validationRules = [
        'username'  => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'student_id'=> 'permit_empty|alpha_numeric|min_length[6]|max_length[20]|is_unique[users.student_id,user_id,{user_id}]', 
        'password'  => 'required|min_length[6]',
        'role'      => 'required|in_list[admin,student,librarian]',
        'photo'     => 'permit_empty|valid_url',
        'firstname' => 'required|alpha_space|min_length[2]|max_length[50]',
        'lastname'  => 'required|alpha_space|min_length[2]|max_length[50]',
        'course'    => 'required|alpha_space|min_length[2]|max_length[50]',
        'year'      => 'required|alpha_numeric_space|min_length[1]|max_length[20]',
        'email'     => 'required|valid_email|is_unique[users.email,user_id,{user_id}]' 
    ];
    
    protected $validationMessages = [
        'username' => [
            'required'   => 'Username is required.',
            'alpha_numeric' => 'Username must contain only letters and numbers.',
            'min_length' => 'Username must be at least 3 characters long.',
            'max_length' => 'Username cannot exceed 50 characters.',
            'is_unique'  => 'Username is already taken.',
        ],
        'student_id' => [
            'permit_empty' => 'Student ID can be left empty.',
            'alpha_numeric' => 'Student ID must be alphanumeric.',
            'min_length' => 'Student ID must be at least 6 characters long.',
            'max_length' => 'Student ID cannot exceed 20 characters.',
            'is_unique' => 'Student ID is already in use.',
        ],
        'password' => [
            'required'   => 'Password is required.',
            'min_length' => 'Password must be at least 6 characters long.',
        ],
        'role' => [
            'required' => 'Role is required.',
            'in_list'  => 'Role must be either admin or student.',
        ],
        'photo' => [
            'valid_url' => 'Photo must be a valid URL.',
        ],
        'firstname' => [
            'required'   => 'First name is required.',
            'alpha_space'=> 'First name can only contain letters and spaces.',
            'min_length' => 'First name must be at least 2 characters long.',
            'max_length' => 'First name cannot exceed 50 characters.',
        ],
        'lastname' => [
            'required'   => 'Last name is required.',
            'alpha_space'=> 'Last name can only contain letters and spaces.',
            'min_length' => 'Last name must be at least 2 characters long.',
            'max_length' => 'Last name cannot exceed 50 characters.',
        ],
        'course' => [
            'required'   => 'Course is required.',
            'alpha_space'=> 'Course name can only contain letters and spaces.',
            'min_length' => 'Course name must be at least 2 characters long.',
            'max_length' => 'Course name cannot exceed 50 characters.',
        ],
        'year' => [
            'required' => 'Year is required.',
            'alpha_numeric_space' => 'Year can only contain letters, numbers, and spaces.',
            'min_length' => 'Year must be at least 1 character long.',
            'max_length' => 'Year cannot exceed 20 characters.',
        ],
        'email' => [
            'required'   => 'Email is required.',
            'valid_email'=> 'Please provide a valid email address.',
            'is_unique'  => 'This email address is already in use.',
        ],
    ];
    
    protected $skipValidation = false; 

    
    public function findUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function findUserByStudentId($student_id)
    {
        return $this->where('student_id', $student_id)->first(); 
    }

    public function verifyPassword($inputPassword, $storedPassword)
    {
        
        return password_verify($inputPassword, $storedPassword);
    }

    public function createUser(array $data)
    {
        
        if ($this->where('username', $data['username'])->first()) {
            log_message('error', 'Username already exists: ' . $data['username']);
            return false; 
        }

        if ($this->where('email', $data['email'])->first()) {
            log_message('error', 'Email already exists: ' . $data['email']);
            return false; 
        }

        
        log_message('info', 'Password hash received: ' . $data['password']); 
        
        
        $db = \Config\Database::connect(); 
        $builder = $db->table('users'); 

        
        $inserted = $builder->insert($data);
        
        if ($inserted) {
            log_message('info', 'User registered successfully: ' . $data['username']);
            return true;
        } else {
            log_message('error', 'Failed to register user: ' . print_r($db->error(), true));
            return false;
        }
    }
}
