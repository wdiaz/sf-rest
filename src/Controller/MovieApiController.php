<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class MovieApiController
 * @package App\Controller
 */
class MovieApiController extends AbstractController
{
    protected $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
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

	/**
	 * @param string $message
	 * @return JsonResponse
	 */
	public function respondUnauthorized($message = 'Not Authorized!'): JsonResponse
	{
		return $this->setStatusCode(401)->respondWithErros($message);
    }
}
