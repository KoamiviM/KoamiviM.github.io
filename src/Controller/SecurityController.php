<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $urlGenerator;


    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository,  \Swift_Mailer $mailer)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/authentication/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, UrlGeneratorInterface $urlGenerator): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $register = $urlGenerator->generate('user_registration');

        return $this->render('security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'register' => $register
            ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/authentication/forgot_password/", name="forgot_password")
     */
    public function forgot_password()
    {
        $password_reset_request = $this->urlGenerator->generate('password_reset_request');
        return $this->render('security/forgot_password.html.twig', [ "password_reset_request" => $password_reset_request]);
    }

    /**
     * @Route("/authentication/password_reset_request", name="password_reset_request", methods={"POST"})
     */
    public function send_password_reset_email(Request $request,  \Swift_Mailer $mailer): Response
    {
        $email = $request->get('email');

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if($user == null)
        {
            $this->addFlash('error', 'There is no user linked to the email '.$email);
            return $this->redirectToRoute('app_login');
        }

        if(!$user->getIsActive())
        {
            $this->addFlash('error', 'The account linked to the email '.$email.' is not active yet.');
            return $this->redirectToRoute('app_login');
        }

        if(!$user->getIsLocked())
        {
            $this->addFlash('error', 'The account linked to the email '.$email.' is locked.');
            return $this->redirectToRoute('app_login');
        }

        $message = (new \Swift_Message('Password reset'))
            ->setFrom('noreply@vitrine.fr') // to be checked
            ->setTo('aristide.nuwokpe@gmail.com') //->setTo($email)
            ->setBody(
                $this->renderView(
                    'security/change_password_email.html.twig',
                    [
                        'username' => $user->getUsername(),
                        'reset_link' => $this->urlGenerator->generate('reset_password', ['email' => $user->getEmail()]),
                        'homepage_link' => $this->urlGenerator->generate('homepage')
                    ]
                ),
                'text/html'
            )
            ->setCharset('utf-8');

        $mailer->send($message);
        $this->addFlash('success', 'Password reset link is sent to '.$email.'. Please check your email and follow the link');
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/authentication/password_reset/{email}", name="reset_password", methods={"GET"})
     */
    public function reset_password(Request $request): Response
    {
        $id =  $request->get('email');
        $changePassword = $this->urlGenerator->generate('changePassword');
        return $this->render('security/change_password.html.twig',
            [ 'email' => $id, 'changePassword'=>$changePassword]);

    }

    /**
     * @Route("/authentication/password_reset", name="changePassword", methods={"POST"})
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $email =  $request->get('email');

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if($user == null)
            return $this->redirectToRoute('app_login');

        $password =  $request->get('password');
        $user = new User();
        $password = $passwordEncoder->encodePassword($user, $password);
        $this->userRepository->changePassword($email, $password);

        $this->addFlash('success', ' Your password has been reset successfully');

        return $this->redirectToRoute('app_login');
    }
}
