<?php


namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    private $repository;
    private EntityManagerInterface $entityManager;


    public function __construct(CompanyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->entityManager = $em;
    }

    /**
     * @Route("/account/p/company", name="pro.companies")
     * @param Request $request
     * @return Response
     */
    public function Companies(Request $request): Response
    {
        $company = new Company();
        $company->setUser($this->getUser());

        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $this->entityManager->persist($company);
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $e){
                $this->addFlash('error', ' Vous possedez déjà une entreprise à ce nom ');
                return $this->redirectToRoute('pro.companies');
            }

            $this->addFlash('success', ' La nouvelle entreprise a bien été crée');
            return $this->redirectToRoute('pro.companies');
        }

        $companies = $this->repository->findBy(['user' => $this->getUser()]);

        return $this->render('back_end/companies/companies.html.twig',
        [
            'action' => 'add',
            'company' => $company,
            'companies' => $companies,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/p/company/edit/{id}", name="pro.company.edit")
     * @param Request $request
     * @param Company $company
     * @return Response
     */
    public function CompanyEdit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $e){
                $this->addFlash('error', ' Vous possedez déjà une entreprise à ce nom ');
                return $this->redirectToRoute('pro.companies');
            }

            $this->addFlash('success', ' L\'entreprise a bien été modifiée');
            return $this->redirectToRoute('pro.companies');
        }

        $companies = $this->repository->findBy(['user' => $this->getUser()]);

        return $this->render('back_end/companies/companies.html.twig',
            [
                'action' => 'edit',
                'company' => $company,
                'companies' => $companies,
                'form' => $form->createView()
            ]);

    }

    /**
     * @Route("/account/p/company/delete/{id}", name="pro.company.delete")
     * @param Request $request
     * @param Company $company
     * @return Response
     */
    public function CompanyDelete(Request $request, Company $company): Response
    {
        try {
            $this->entityManager->remove($company);
            $this->entityManager->flush();
        }catch (ForeignKeyConstraintViolationException $ex){
            $this->addFlash('error', 'Supression impossible: l\'entreprise possède des éléments associés ');
            return $this->redirectToRoute('pro.companies');
        }

        $this->addFlash('success', ' L\'entreprise a bien été supprimée ');

        return $this->redirectToRoute('pro.companies');
    }
}