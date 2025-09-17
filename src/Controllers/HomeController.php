<?php

namespace OnePieceTCGCollect\src\Controllers;

class HomeController
{
    public function index()
{

    ob_start();
    require __DIR__ . '/../../views/layout/home.php';
    $content = ob_get_clean();

    
    require __DIR__ . '/../../views/layout.php';
}}
