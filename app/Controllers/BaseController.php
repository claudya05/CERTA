<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Base Controller khusus project CERTA.
 * Semua controller modul (Dashboard, DataPengecekan, AnalisisData, FormCerta) extend dari sini.
 */
abstract class BaseController extends Controller
{
    protected $helpers = ['url', 'form', 'certa'];

    protected $request;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }
}
