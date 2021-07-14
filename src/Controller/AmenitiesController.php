<?php


namespace App\Controller;

use App\Entity\Amenity;
use App\Form\AmenityType;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AmenityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmenitiesController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AmenityRepository $repository;

    public function __construct(AmenityRepository $repository, EntityManagerInterface $em){
        $this->repository = $repository;
        $this->entityManager = $em;
    }

    /**
     * @Route("/account/a/amenities", name="admin.amenities")
     * @param Request $request
     * @return Response
     */
    public function Index(Request $request, CategoryRepository $categoryRepository) : Response
    {
        $amenity = new Amenity();
        $amenity->setUpdatedAt(new \DateTime());
        $form = $this->createForm(AmenityType::class, $amenity);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try{
                if($amenity->getCategory()->getParent()->getId() == 5) // For "Entreprise"
                    $amenity->setForcompany(true);
                else
                    $amenity->setForcompany(false);

                $this->entityManager->persist($amenity);
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $ex){
                $this->addFlash('error', ' La commodité existe déjà pour cette catégorie ');
                return $this->redirectToRoute('admin.amenities');
            }

            $this->addFlash('success', ' La nouvelle commodité a bien été ajoutée ');

            return $this->redirectToRoute('admin.amenities');
        }

        $categories = $categoryRepository->findAll();

        return $this->render('back_end/amenities/amenities.html.twig',
            [
                'action' => 'add',
                'categories' => $categories,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/a/amenities/{id}", name="admin.amenities.edit")
     * @param Request $request
     * @param Amenity $amenity
     * @return Response
     */
    public function Edit(Request $request, Amenity $amenity) : Response
    {
        $form = $this->createForm(AmenityType::class, $amenity);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $amenity->setUpdatedAt(new \DateTime());
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $ex){
                $this->addFlash('error', ' La commodité existe déjà pour cette catégorie ');
                return $this->redirectToRoute('admin.amenities');
            }

            $this->addFlash('success', ' La commodité a bien été modifiée ');

            return $this->redirectToRoute('admin.amenities');
        }

        $amenities = $this->repository->findAll();

        return $this->render('back_end/amenities/amenities.html.twig',
            [
                'action' => 'edit',
                'amenities' => $amenities,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/a/amenity/delete/{id}", name="admin.amenity.delete")
     * @param Amenity $amenity
     * @return Response
     */
    public function Delete(Amenity $amenity) : Response
    {
        try {
            $this->entityManager->remove($amenity);
            $this->entityManager->flush();
        }catch (ForeignKeyConstraintViolationException $ex){
            $this->addFlash('error', 'Supression impossible: la commodité est associée à une annonce ');
            return $this->redirectToRoute('admin.amenities');
        }

        $this->addFlash('success', ' La commodité a bien été supprimée ');

        return $this->redirectToRoute('admin.amenities');
    }
}