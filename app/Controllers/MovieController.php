<?php

namespace project\app\Controllers;
use project\services\MovieService;
use project\template\MyTemplate;

class MovieController
{
    private MovieService $movieService;

    private MyTemplate $myTemplate;

    public function __construct()
    {
        $this->movieService = new MovieService();

        $this->myTemplate = new MyTemplate();
    }

    public function showMovies(): void
    {
        echo $this->myTemplate->view(
            'D:\WebData\project\public\html\home.html',
            $this->movieService->showMovies()
        );
    }
}
