<?php

namespace App\Controller\Web;

use App\Entity\User;
use App\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends BaseController
{
    /**
     * @Route("/register", name="user_register", methods={"GET"})
     */
    public function register()
    {
        if ($this->isUserLoggedIn()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('register.html.twig', array('user' => new User()));
    }

    /**
     * @Route("/register", name="user_register_handle", methods={"POST"})
     */
    public function registerHandleAction(Request $request)
    {
        $errors = array();

        if (!$email = $request->request->get('email')) {
            $errors[] = '"email" is required';
        }
        if (!$plainPassword = $request->request->get('plainPassword')) {
            $errors[] = '"password" is required';
        }
        if (!$username = $request->request->get('username')) {
            $errors[] = '"username" is required';
        }

        $userRepository = $this->getUserRepository();

        // make sure we don't already have this user!
        if ($existingUser = $userRepository->findUserByEmail($email)) {
            $errors[] = 'A user with this email is already registered!';
        }

        // make sure we don't already have this user!
        if ($existingUser = $userRepository->findUserByUsername($username)) {
            $errors[] = 'A user with this username is already registered!';
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $encodedPassword = $this->container->get('security.password_encoder')
            ->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        // errors? Show them!
        if (count($errors) > 0) {
            return $this->render('register.html.twig', array('errors' => $errors, 'user' => $user));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->loginUser($user);

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/login", name="security_login_form")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->isUserLoggedIn()) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        $lastUsername = $authenticationUtils->getLastUserName();
        $lastError = $authenticationUtils->getLastAuthenticationError();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render('user/login.html.twig', array(
            'error'         => $lastError,
            'form'          => $form->createView()
        ));
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
        throw new \Exception('Should not get here - this should be handled magically by the security system!');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Should not get here - this should be handled magically by the security system!');
    }
}
