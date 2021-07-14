<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Swift_Mailer;

class RegistrationController extends AbstractController
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
     * @Route("/authentication/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $user = new User();
        $user->setIsLocked(false);
        $user->setIsActive(false);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setCreatedAt();

            try{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch (UniqueConstraintViolationException $ex){
                $this->addFlash('error', 'Les identifiants email/nom d\'utilisateur existent déjà');
                return $this->redirectToRoute('user_registration');
            }


            $message = (new \Swift_Message('Account activation'))
                ->setFrom('noreply@vitrine.fr') // to be checked
                ->setTo('aristide.nuwokpe@gmail.com') //($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'security/registration_email.html.twig',
                        [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'validation_link' => $this->urlGenerator->generate('user_confirm', ['email' => $user->getEmail()]),
                            'homepage_link' => $this->urlGenerator->generate('homepage')
                            ]
                    ),
                    'text/html'
                )
                ->setCharset('utf-8');

            $mailer->send($message);

            // return registration success
            return  $this->render(
                'security/registration_success.html.twig',
                [
                  'username' => $user->getUsername(),
                  'email'=> $user->getEmail()
                ]
            );
        }

        return $this->render(
            'security/register.html.twig',
            [
              'form' => $form->createView(),
              'login'=> $this->urlGenerator->generate('app_login')
            ]
        );
    }

    /**
     * @Route("/authentication/activate_account/{email}", name="user_confirm", methods={"GET"})
     */
    public function confirmEmail(Request $request): Response
    {
        $id = $request->get('email');
        $this->userRepository->confirmEmail($id);
        $this->addFlash('success', 'Your account is now active');
        return $this->redirectToRoute('app_login');
    }
}
