<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\DefaultCategory;
use App\Entity\Listing;
use App\Entity\User;
use App\Form\HebergementListingType;
use App\Form\ListingType;
use App\Form\RestaurantListingType;
use App\Repository\ListingRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ListingController extends AbstractController
{
    private ListingRepository $repository;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private User $current_user;

    public function __construct(ListingRepository $repository, EntityManagerInterface $em, Security $security)
    {
        $this->repository = $repository;
        $this->entityManager = $em;
        $this->security = $security;
        $this->current_user = $this->security->getUser();
    }

    /**
     * @Route("/account/p/listings", name="pro.listings")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function Index(Request $request, PaginatorInterface $paginator) : Response
    {
        $listings = $paginator->paginate(
            $this->repository->findByCriteria($this->current_user->getId()),
            $request->query->getInt('page', 1),
            10);

        return $this->render('back_end/listings/listings.html.twig',
        [
            'listings' => $listings
        ]);
    }

    /**
     * @Route("/account/p/listing/show/{id}", name="pro.listing.show")
     * @param Listing $listing
     * @return Response
     */
    public function Show(Listing $listing) : Response
    {
        return $this->render('back_end/listings/listing.show.html.twig',
            [
                'default_category' => $listing->getCategory()->getParent()->getId(),
                'listing' => $listing
            ]);
    }

    /**
     * @Route("/account/p/listing/add/{id}", name="pro.listing.add")
     * @param DefaultCategory $defaultCategory
     * @param Request $request
     * @return Response
     */
    public function Add(DefaultCategory $defaultCategory, Request $request) : Response
    {
        $listing = new Listing();

        if($this->current_user->getCompanies()->count() == 1){
            $listing->setCompany( $this->current_user->getCompanies()->first());
        }

        $listing->setCreatedAt(new \DateTime());
        $listing->setIsActive(false);
        $listing->setIsApproved(false);

        if($defaultCategory->getId() == 1)
            $form = $this->createForm(HebergementListingType::class, $listing);
        else if($defaultCategory->getId() == 2)
            $form = $this->createForm(RestaurantListingType::class, $listing);
        else if($defaultCategory->getId() == 4)
            $form = $this->createForm(RestaurantListingType::class, $listing);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $listing->setLastUpdatedAt(new \DateTime());

            try {
                $this->entityManager->persist($listing);
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $e){
                $this->addFlash('error', ' Vous possedez déjà une annonce à ce nom ');
                return $this->redirectToRoute('pro.listing.add', ['id' => 1]);
            }

            $this->addFlash('success', ' Votre annonce a bien été ajoutée ');
            return $this->redirectToRoute('pro.listings');
        }

        return $this->render('back_end/listings/add_listing.html.twig',
            [
                'action' => "add",
                'listing' => $listing,
                'default_category' => $defaultCategory->getId(),
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/account/p/listing/edit/{id}", name="pro.listing.edit")
     * @param Listing $listing
     * @param Request $request
     * @return Response
     */
    public function Edit(Listing $listing, Request $request) : Response
    {
        if($listing->getCategory()->getParent()->getId() == 1)
            $form = $this->createForm(HebergementListingType::class, $listing);
        else if($listing->getCategory()->getParent()->getId() == 2)
            $form = $this->createForm(RestaurantListingType::class, $listing);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try {
                $listing->setLastUpdatedAt(new \DateTime());
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $e){
                $this->addFlash('error', ' Vous possedez déjà une annonce à ce nom ');
                return $this->redirectToRoute('pro.listing.edit', ['id' => 1]);
            }

            $this->addFlash('success', ' Votre annonce a bien été mise à jour ');
            return $this->redirectToRoute('pro.listings');
        }

        return $this->render('back_end/listings/add_listing.html.twig',
            [
                'action' => "edit",
                'listing' => $listing,
                'default_category' => $listing->getCategory()->getParent()->getId(),
                'form' => $form->createView()
            ]);
    }


    /**
     * @Route("/account/p/listing/activate", name="pro.listing.activate")
     * @return Response
     */
    public function Activate() : Response
    {
        return $this->render('back_end/listings/activate_listing.html.twig');
    }

    /**
     * @Route("/account/p/listing/approve", name="pro.listing.approve")
     * @return Response
     */
    public function Approve() : Response
    {
        return $this->render('back_end/listings/approve_listing.html.twig');
    }
}