<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';           // Table name
    protected $primaryKey = 'user_id';    // Primary key column

    protected $allowedFields = [
        'username', 
        'password', 
        'email',  // Added email field
        'role', 
        'photo', 
        'firstname',  // Added firstname
        'lastname',   // Added lastname
        'course',     // Added course
        'year',       // Added year
        'created_at'  // Created at (handled by timestamps)
    ]; // Fields allowed for mass assignment

    // Enable automatic timestamps for creation and updates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at'; // Field for storing creation timestamps
    protected $updatedField  = 'updated_at'; // Field for storing update timestamps

    // Validation rules for data integrity
    protected $validationRules = [
        'username'  => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'password'  => 'required|min_length[6]',
        'role'      => 'required|in_list[admin,student]',
        'photo'     => 'permit_empty|valid_url',
        'firstname' => 'required|alpha_space|min_length[2]|max_length[50]',
        'lastname'  => 'required|alpha_space|min_length[2]|max_length[50]',
        'course'    => 'required|alpha_space|min_length[2]|max_length[50]',
        'year'      => 'required|alpha_numeric_space|min_length[1]|max_length[20]',
        'email'     => 'required|valid_email|is_unique[users.email,user_id,{user_id}]' // Added validation for email
    ];
    
    protected $validationMessages = [
        'username' => [
            'required'   => 'Username is required.',
            'alpha_numeric' => 'Username must contain only letters and numbers.',
            'min_length' => 'Username must be at least 3 characters long.',
            'max_length' => 'Username cannot exceed 50 characters.',
            'is_unique'  => 'Username is already taken.',
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
    
    protected $skipValidation = false; // Always validate data before saving

    // Custom methods
    public function findUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function verifyPassword($inputPassword, $storedPassword)
    {
        // Use PHP password hashing for secure comparison
        return password_verify($inputPassword, $storedPassword);
    }

    public function createUser(array $data)
    {
        // Check if username or email already exists
        if ($this->where('username', $data['username'])->first()) {
            log_message('error', 'Username already exists: ' . $data['username']);
            return false; // Return false if username exists
        }

        if ($this->where('email', $data['email'])->first()) {
            log_message('error', 'Email already exists: ' . $data['email']);
            return false; // Return false if email exists
        }

        // Assume the password is already hashed
        log_message('info', 'Password hash received: ' . $data['password']); // Debugging
        
        // Attempt to save the user data
        $db = \Config\Database::connect(); // Get the DB connection
        $builder = $db->table('users'); // Assume 'users' is your table name

        // Check if the insert operation succeeds
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
