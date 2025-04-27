<?php

namespace project\app\Controllers;
use project\services\MovieService;
use project\template\ThisTemplate;

class MovieController
{
    private MovieService $movieService;

    private ThisTemplate $thisTemplate;

    public function __construct()
    {
        $this->movieService = new MovieService();

        $this->thisTemplate = new ThisTemplate();
    }

    public function showMovies(): void
    {
        echo $this->thisTemplate->view(
            'D:\WebData\project\public\html\home.html',
            $this->movieService->showMovies()
        );
    }
}
