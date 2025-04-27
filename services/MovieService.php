<?php

namespace project\services;
class MovieService
{


    public function showMovies(): array {

//        $imageBaseUrl = 'http://localhost/project/public/img/';

        $imageBaseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/project/public/img/';

        $movies = [
            ['name' => 'История игрушек', 'year' => '1995', 'type' => 'Мультфильм', 'description' => '...', 'image' => $imageBaseUrl . 'toy_story.png'],
            ['name' => 'В поисках Немо', 'year' => '2003', 'type' => 'Мультфильм', 'description' => '...', 'image' => $imageBaseUrl . 'finding_nemo.png'],
            ['name' => 'Приключения Паддингтона', 'year' => '2014', 'type' => 'Семейный', 'description' => '...', 'image' => $imageBaseUrl . 'paddington.png'],
        ];


        $data = ['movies' => $movies];
        return $data;

    }
}