<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\FilterAdminUserType;
use App\Form\UserProfileType;
use App\Form\UserProProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UsersController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    private User $currentUser;
    private UserRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, UserRepository $repository, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->repository = $repository;
        $this->entityManager = $em;

        $this->currentUser = $this->security->getUser();
    }

    /**
     * @Route("/account/profile", name="app.profile")
     * @return Response
     */
    public function profile(Request $request, UrlGeneratorInterface $urlGenerator) : Response
    {
        $user = $this->repository->findOneBy(['email' => $this->currentUser->getEmail()]);

        if($this->security->isGranted('ROLE_PRO'))
            $form = $this->createForm(UserProProfileType::class, $user);
        else
            $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $user->setUpdatedAt();

                $this->entityManager->flush();

                $this->addFlash('success', 'Your profile was successfully updated');

            }catch (UniqueConstraintViolationException $e){
                $this->addFlash('error', 'This username is already in use');
            }

            return $this->redirectToRoute('app.profile');
        }

        $changePassword = $urlGenerator->generate('profile.changePassword');

        return $this->render('front_end/user_account/profile.html.twig',
        [
            'user' => $user,
            'form_profile' => $form->createView(),
            'changePassword'=>$changePassword
        ]);
    }

    /**
     * @Route("/account/password_reset", name="profile.changePassword", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function profileChangePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $email =  $request->get('email');

        $user = $this->repository->findOneBy(['email' => $email]);

        $old_password = $request->get('old_password');
        $new_password =  $request->get('new_password');

        $checkPass = $passwordEncoder->isPasswordValid($user, $old_password);

        if($checkPass === true)
        {
            $user = new User();
            $new_password = $passwordEncoder->encodePassword($user, $new_password);
            $this->repository->changePassword($email, $new_password);

            $this->addFlash('success', ' Your password has been reset successfully');
        }else
        {
            $this->addFlash('error', ' The current password is incorrect');
        }

        return $this->redirectToRoute('app.profile');
    }

    /**
     * @Route("/account/a/users", name="admin.users")
     */
    public function users(Request $request, PaginatorInterface $paginator): Response
    {
        $page_counter = 10;

        $filter_user = new User();
        $filter_user->setIsActive(true);
        $filter_user->setIsLocked(false);

        $filter_form = $this->createForm(FilterAdminUserType::class, $filter_user);

        $filter_form->handleRequest($request);
        if($filter_form->isSubmitted())
        {
            $page_counter = $request->request->get('counter');
        }

        $users = $paginator->paginate(
            $this->repository
                ->findByCriteria($filter_user->getIsActive(), $filter_user->getIsLocked(), $filter_user->getRoles()),
            $request->query->getInt('page', 1),
            $page_counter);

        return $this->render('back_end/users/users.html.twig',
        [
            'users' => $users,
            'filter_form' => $filter_form->createView()
        ]);
    }

    /**
     * @Route("/account/a/lock_unlock_user/{id}", name="admin.lock.unlock.user")
     */
    public function lockUser(Request $request, User $user): Response
    {
        $user->setIsLocked(!$user->getIsLocked());
        $action = $user->getIsLocked() == true ? "bloqué":"débloqué";

        $this->entityManager->flush();

        $this->addFlash('success', 'Le compte utilisateur a bien été '.$action);

        return $this->redirectToRoute('admin.users');
    }
}
