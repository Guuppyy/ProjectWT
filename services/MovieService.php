<?php

namespace project\services;
use project\database\EntityManager;
class MovieService
{


    public function showMovies(): array {

        // $movie = $this->entityManager->getRepository(Movie::class)->find($id);

        //        if (!$movie) {
        //            // Если фильм не найден, можем вернуть 404
        //            header("HTTP/1.0 404 Not Found");
        //            echo "404 Not Found: Movie not found.";
        //            return;
        //        }

        //        $data = [
        //            'name' => $movie->getName(),
        //            'year' => $movie->getYear(),
        //            'type' => $movie->getType(),
        //            'description' => $movie->getDescription(),
        //            'image' => $movie->getImageUrl(),
        //        ];
        $movies = [
                ['name' => 'История игрушек', 'year' => '1995', 'type' => 'Мультфильм', 'description' => '...', 'image' => __DIR__ . '/../public/img/toy_story.png'],
                ['name' => 'В поисках Немо', 'year' => '2003', 'type' => 'Мультфильм', 'description' => '...', 'image' => __DIR__ . '/../public/img/finding_nemo.png'],
                ['name' => 'Паддингтон', 'year' => '2014', 'type' => 'Семейный', 'description' => '...', 'image' => __DIR__ . '/../public/img/paddington.png'],
        ];
        $data = ['movies' => $movies];
        return $data;

    }
}