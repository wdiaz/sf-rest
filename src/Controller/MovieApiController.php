<?php

namespace App\Controller;

use App\Entity\Movie;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MovieApiController.
 */
class MovieApiController extends AbstractController
{
    protected $statusCode = 200;

    /**
     * @Route("/movies/{page}", name="movie_index", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function index(Request $request, int $page = 1)
    {
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $qb = $movieRepository->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerFanta = new Pagerfanta($adapter);
        $pagerFanta->setMaxPerPage(5);
        $pagerFanta->setCurrentPage($page);
        $result = $pagerFanta->getCurrentPageResults();
        if(empty($result)) {
        	throw $this->createNotFoundException();
        }

        foreach ($result as $movie) {
            $movies[] = $movieRepository->transform($movie);
        }

        return $this->respond($movies);
    }

    public function create(Request $request)
    {
        $request = $this->transformJsonBody($request);
        if (!$request) {
            return $this->respondValidationError('Please, provide a valid request');
        }

        if (!$request->get('title')) {
            return $this->respondValidationError('Please, provide a valid title');
        }
        $movie = new Movie();
        $movie->setTitle($request->get('title'));
        $movie->setCount(0);
        $em = $this->getDoctrine()->getManager();
        $movieRepository = $em->getRepository(Movie::class);
        $em->persist($movie);
        $em->flush();

        return $this->respondCreated($movieRepository->transform($movie));
    }

    public function increaseCount($id)
    {
        $em = $this->getDoctrine()->getManager();
        $movieRepository = $em->getRepository(Movie::class);
        if (false === ($movie = $movieRepository->find($id))) {
            return $this->respondNotFound();
        }
        $movie->setCount($movie->getCount() + 1);
        $em->persist($movie);
        $em->flush();

        return $this->respond([
            'count' => $movie->getCount(),
        ]);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $errors
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function respondWithErrors($errors, $headers = []): JsonResponse
    {
        return new JsonResponse([
            [
                'errors' => $errors,
            ],
            $this->getStatusCode(),
            $headers,
        ]);
    }

    public function respond($data, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized($message = 'Not Authorized!'): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithErros($message);
    }

    public function respondValidationError($message = 'Validation Errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }

    private function transformJsonBody(Request $request)
    {
        if (null === ($data = json_decode($request->getContent(), true)) ||
            JSON_ERROR_NONE !== json_last_error()
        ) {
            return null;
        }

        $request->request->replace($data);

        return $request;
    }
}
