<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\Battle;
use App\Entity\Programmer;
use App\Entity\Project;
use App\Repository\ProgrammerRepository;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\BattleRepository;
use App\Repository\ApiTokenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

abstract class BaseController extends AbstractController
{
    /**
     * Is the current user logged in?
     *
     * @return boolean
     */
    public function isUserLoggedIn()
    {
        return $this->container->get('security.authorization_checker')
            ->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * Logs this user into the system
     *
     * @param User $user
     */
    public function loginUser(User $user)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
    }

    public function addFlashMessage($message, $positiveNotice = true)
    {
        /** @var Request $request */
        $noticeKey = $positiveNotice ? 'notice_happy' : 'notice_sad';
        $this->addFlash($noticeKey, $message);
    }

    /**
     * Used to find the fixtures user - I use it to cheat in the beginning
     *
     * @param $username
     * @return User
     */
    public function findUserByUsername($username)
    {
        return $this->getUserRepository()->findUserByUsername($username);
    }

    /**
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return $this->getDoctrine()
            ->getRepository(User::class);
    }

    /**
     * @return ProgrammerRepository
     */
    protected function getProgrammerRepository()
    {
        return $this->getDoctrine()
            ->getRepository(Programmer::class);
    }

    /**
     * @return ProjectRepository
     */
    protected function getProjectRepository()
    {
        return $this->getDoctrine()
            ->getRepository(Project::class);
    }

    /**
     * @return BattleRepository
     */
    protected function getBattleRepository()
    {
        return $this->getDoctrine()
            ->getRepository(Battle::class);
    }

    /**
     * @return ApiTokenRepository
     */
    protected function getApiTokenRepository()
    {
        return $this->getDoctrine()
            ->getRepository(ApiToken::class);
    }
}
