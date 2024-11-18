<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];


    

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------


    public $student_registration = [
        'username' => [
            'rules' => 'required|is_unique[users.username]',
            'errors' => [
                'required' => 'Username is required.',
                'is_unique' => 'The username already exists. Please choose another.',
            ],
        ],
        'password' => [
            'rules' => 'required|min_length[6]',
            'errors' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 6 characters long.',
            ],
        ],
        'confirm_password' => [
            'rules' => 'required|matches[password]',
            'errors' => [
                'required' => 'Please confirm your password.',
                'matches' => 'Passwords do not match.',
            ],
        ],
        'firstname' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'First name is required.',
            ],
        ],
        'lastname' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Last name is required.',
            ],
        ],
        'course' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Course is required.',
            ],
        ],
        'year' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Year is required.',
            ],
        ],
    ];
    
    
}
