<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public $fromEmail  = 'no-reply@supremopkv';
    public $fromName   = 'Library System';
    public $recipients = '';

    public $protocol    = 'smtp';
    public $SMTPHost    = 'smtp.gmail.com';
    public $SMTPUser    = 'supremopkv@gmail.com';
    public $SMTPPass    = 'nifa rhau lxqn whbf';
    public $SMTPPort    = 587;
    public $SMTPCrypto  = 'tls';

    public $mailType = 'html';
    public $charset  = 'utf-8';
    public $wordWrap = true;
    public $newline  = "\r\n";
}
