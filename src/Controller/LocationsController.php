<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Location;
use App\Form\CategoryType;
use App\Form\CityType;
use App\Form\CountryType;
use App\Form\LocationType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\LocationRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationsController extends AbstractController
{
    private LocationRepository $locationRepository;
    private CountryRepository $countryRepository;
    private CityRepository $cityRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(LocationRepository $locationRepository, CountryRepository $countryRepository, CityRepository $cityRepository, EntityManagerInterface $em)
    {
        $this->locationRepository = $locationRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->entityManager = $em;
    }

    /**
     * @Route("/account/p/locations", name="pro.locations")
     * @param Request $request
     * @return Response
     */
    public function Index(Request $request) : Response
    {
        $location = new Location();
        $location->setCreatedAt(new \DateTime());
        $location->setLastUpdatedAt(new \DateTime());

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $location->setCountry($location->getCity()->getCountry());
            $this->entityManager->persist($location);
            $this->entityManager->flush();

            return $this->redirectToRoute('pro.locations');
        }

        $locations = $this->locationRepository->findAll();
        return $this->render(
            'back_end/locations/locations.html.twig',
            [
                'action' => "add",
                'location' => "",
                'locations' => $locations,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/p/locations/edit/{id}", name="pro.locations.edit")
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function EditLocation(Location $location, Request $request) : Response
    {
        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $location->setLastUpdatedAt(new \DateTime());
            $this->entityManager->flush();

            return $this->redirectToRoute('pro.locations');
        }

        $locations = $this->locationRepository->findAll();

        return $this->render('back_end/locations/locations.html.twig',
            [
                'action' => "edit",
                'location' => $location,
                'locations' => $locations,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/p/locations/delete/{id}", name="pro.locations.delete")
     * @param Location $location
     * @return Response
     */
    public function DeleteLocation(Location $location) : Response
    {
        $this->entityManager->remove($location);
        $this->entityManager->flush();

        return $this->redirectToRoute('pro.dashboard.locations');
    }

    /**
     * @Route("/account/a/locations/countries", name="admin.locations.countries")
     * @param Request $request
     * @return Response
     */
    public function Countries(Request $request) : Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try {
                $this->entityManager->persist($country);
                $this->entityManager->flush();
            }catch(UniqueConstraintViolationException $ex){
                $this->addFlash('error', ' Ce pays existe déjà ');
                return $this->redirectToRoute('admin.locations.countries');
            }

            $this->addFlash('success', ' Le nouveau pays a bien été ajouté ');
            return $this->redirectToRoute('admin.locations.countries');
        }

        $countries = $this->countryRepository->findAll();

        return $this->render('back_end/locations/countries.html.twig',
            [
                'action' => 'add',
                'countries' => $countries,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/a/locations/country/{id}", name="admin.locations.country.edit")
     * @param Request $request
     * @param Country $country
     * @return Response
     */
    public function EditCountry(Request $request, Country $country) : Response
    {
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $this->entityManager->flush();
            }catch(UniqueConstraintViolationException $ex){
                $this->addFlash('error', ' Ce pays existe déjà ');
                return $this->redirectToRoute('admin.locations.countries');
            }

            $this->addFlash('success', ' Le pays a bien été modifié ');
            return $this->redirectToRoute('admin.locations.countries');
        }

        $countries = $this->countryRepository->findAll();

        return $this->render('back_end/locations/countries.html.twig',
            [
                'action' => 'edit',
                'countries' => $countries,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/a/locations/delete_country/{id}", name="admin.locations.delete_country")
     * @param Country $country
     * @return Response
     */
    public function DeleteCountry(Country $country) : Response
    {
        try{
            $this->entityManager->remove($country);
            $this->entityManager->flush();
        }catch (ForeignKeyConstraintViolationException $ex){
            $this->addFlash('error', 'Supression impossible: le pays possède au moins une ville ');
            return $this->redirectToRoute('admin.locations.countries');
        }

        $this->addFlash('success', 'Le pays a bien été supprimé ');
        return $this->redirectToRoute('admin.locations.countries');
    }


    /**
     * @Route("/account/a/locations/cities", name="admin.locations.cities")
     * @param Request $request
     * @return Response
     */
    public function Cities(Request $request) : Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try {
                $this->entityManager->persist($city);
                $this->entityManager->flush();
            }catch(UniqueConstraintViolationException $ex){
                $this->addFlash('error', ' La ville existe déjà pour ce pays ');
                return $this->redirectToRoute('admin.locations.cities');
            }

            $this->addFlash('success', ' La nouvelle ville a bien été ajoutée ');

            return $this->redirectToRoute('admin.locations.cities');
        }

        $countries = $this->countryRepository->findAll();

        return $this->render('back_end/locations/cities.html.twig',
            [
                'action' => 'add',
                'countries' => $countries,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/a/locations/city/{id}", name="admin.locations.city.edit")
     * @param Request $request
     * @param City $city
     * @return Response
     */
    public function EditCity(Request $request, City $city) : Response
    {
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $this->entityManager->flush();
            }catch (UniqueConstraintViolationException $ex){
                $this->addFlash('error', ' La ville existe déjà pour ce pays ');
                return $this->redirectToRoute('admin.locations.cities');
            }

            $this->addFlash('success', ' La ville a bien été modifiée ');

            return $this->redirectToRoute('admin.locations.cities');
        }

        $countries = $this->countryRepository->findAll();

        return $this->render('back_end/locations/cities.html.twig',
            [
                'action' => 'edit',
                'countries' => $countries,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/account/a/locations/delete_city/{id}", name="admin.locations.delete_city")
     * @param City $city
     * @return Response
     */
    public function DeleteCity(City $city) : Response
    {
        try {
            $this->entityManager->remove($city);
            $this->entityManager->flush();
        }catch (ForeignKeyConstraintViolationException $ex){
            $this->addFlash('error', 'Supression impossible: la ville est utilisée dans une localisation ');
            return $this->redirectToRoute('admin.locations.cities');
        }

        $this->addFlash('success', ' La ville a bien été supprimée ');

        return $this->redirectToRoute('admin.locations.cities');
    }
}