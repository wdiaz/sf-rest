<?php

namespace App\Controller\Web;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        return $this->render('homepage.twig');
    }
}
