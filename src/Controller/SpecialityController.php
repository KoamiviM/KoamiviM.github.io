<?php


namespace App\Controller;

use App\Entity\Speciality;
use App\Form\SpecialityType;
use App\Repository\SpecialityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialityController extends AbstractController
{
    private $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(SpecialityRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->entityManager = $em;
    }

    /**
     * @Route("/account/a/specialities", name="admin.specialities")
     * @param Request $request
     * @return Response
     */
    public function Specialities(Request $request) : Response
    {
        $speciality = new Speciality();

        $form = $this->createForm(SpecialityType::class, $speciality);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $this->entityManager->persist($speciality);
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $e){
                $this->addFlash('error', ' Cette spécialité existe déjà ');
                return $this->redirectToRoute('admin.specialities');
            }

            $this->addFlash('success', ' La nouvelle spécialité a bien été ajoutée');
            return $this->redirectToRoute('admin.specialities');
        }

        $specialities = $this->repository->findAll();

        return $this->render('back_end/specialities/specialities.html.twig',
            [
                'action' => 'add',
                'specialities' => $specialities,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/account/a/speciality/{id}", name="admin.speciality.edit")
     * @param Request $request
     * @return Response
     */
    public function SpecialityEdit(Request $request, Speciality $speciality) : Response
    {
        $form = $this->createForm(SpecialityType::class, $speciality);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $e){
                $this->addFlash('error', ' Cette spécialité existe déjà ');
                return $this->redirectToRoute('admin.specialities');
            }

            $this->addFlash('success', ' La spécialité a bien été modifiée');
            return $this->redirectToRoute('admin.specialities');
        }

        $specialities = $this->repository->findAll();

        return $this->render('back_end/specialities/specialities.html.twig',
            [
                'action' => 'edit',
                'specialities' => $specialities,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/account/a/speciality/delete/{id}", name="admin.speciality.delete")
     * @param Request $request
     * @return Response
     */
    public function SepcialityDelete(Request $request, Speciality $speciality) : Response
    {
        try {
            $this->entityManager->remove($speciality);
            $this->entityManager->flush();
        }catch (ForeignKeyConstraintViolationException $ex){
            $this->addFlash('error', 'Supression impossible: la spécialité a au moins une annonce associée ');
            return $this->redirectToRoute('admin.specialities');
        }

        $this->addFlash('success', ' La spécialité a bien été supprimée ');

        return $this->redirectToRoute('admin.specialities');
    }
}