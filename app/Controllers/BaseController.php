<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];
    protected $session;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    /**
     * Initialize the controller with shared logic.
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Load the session service
        $this->session = \Config\Services::session();

        // Call the session timeout check
        $this->checkSessionTimeout();
    }

    /**
     * Check for session timeout and handle logout if necessary.
     *
     * @return void
     */
    protected function checkSessionTimeout()
    {
        $currentTime = time();
        $lastActivity = $this->session->get('last_activity') ?? $currentTime;

        // Check if the session is active and has timed out (30 minutes = 1800 seconds)
        if ($this->session->get('logged_in') && ($currentTime - $lastActivity) > 1800) { // 30 minutes timeout
            $this->session->destroy(); // Destroy session
            // Redirect with SweetAlert-friendly parameter for session expiration
            header('Location: ' . base_url('/login?session_expired=1'));
            exit;
        }

        // Update last activity timestamp
        $this->session->set('last_activity', $currentTime);
    }
}
