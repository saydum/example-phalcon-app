<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class HomeController extends Controller
{
    public function indexAction(): void
    {
        $this->view->title = "Главная страница";
    }
}