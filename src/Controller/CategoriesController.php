<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    private CategoryRepository $repository;
    private EntityManagerInterface $entityManager;
    public function __construct(CategoryRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->entityManager = $em;
    }

    /**
     * @Route("/account/a/categories", name="admin.categories")
     * @param Request $request
     * @return Response
     */
    public function Index(Request $request, PaginatorInterface $paginator) : Response
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTime());
        $category->setLastUpdatedAt(new \DateTime());

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', ' La nouvelle catégorie a bien été crée');

            return $this->redirectToRoute('admin.categories');
        }

        $categories = $paginator->paginate(
            $this->repository->findCategories(),
            $request->query->getInt('page', 1),
            10);

        return $this->render(
            'back_end/categories/categories.html.twig',
            [
                'action' => "add",
                'categories' => $categories,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/a/category/edit/{id}", name="admin.categories.edit")
     * @param Category $category
     * @param Request $request
     * @return Response
     */
    public function Edit(Category $category, Request $request, PaginatorInterface $paginator) : Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $category->setLastUpdatedAt(new \DateTime());
            $this->entityManager->flush();

            $this->addFlash('success', ' La catégorie a bien été modifiée');

            return $this->redirectToRoute('admin.categories');
        }

        $categories = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1),
            10);

        return $this->render(
            'back_end/categories/categories.html.twig',
            [
                'action' => "edit",
                'categories' => $categories,
                'form' => $form->createView()
            ]
        );
    }
}