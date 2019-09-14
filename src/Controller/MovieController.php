<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
	/**
	 * @Route("/movies", name="movie_movies")
	 * @param Request $request
	 * @return JsonResponse
	 */
    public function moviesAction(Request $request)
    {
    	return new JsonResponse([
		    'title' => 'The Princess Bride',
		    'count' => 0
	    ]);
    }
}
