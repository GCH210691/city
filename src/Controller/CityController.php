<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/', name: 'city_view')]
    public function ShowCity(CityRepository $cityRepository)
    #populate data in memory that comes from the database
    {
        $showCity = $cityRepository->findAll(); #returns an array of active record models
        #render data to the front page
        return $this->render('city/index.html.twig', [
            'showCity' => $showCity,
        ]);
    }
    #[Route('/delete/{id}', name: 'city_delete')] #delete a city function
    public function DeleteCity($id, CityRepository $cityRepository, ManagerRegistry $managerRegistry) #managerRegistry:  inject the Doctrine service into the controller method.
    {
        $city = $cityRepository->find($id);
        #handle error function
        if ($city == null) {
            $this->addFlash('city not found', 'error');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($city);
            $manager->flush();
            $this->addFlash('Success', 'deleted');

        }
        return $this->redirectToRoute('city_view', [
        ]);
        #go back to the main page if delete sucsessfully
    }
    #[Route('/add', name: 'city_add')] #add a city funcion
    public function AddCity(Request $request, ManagerRegistry $managerRegistry)
    {
        $city = new City;
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($city);
            $manager->flush();
            $this->addFlash('Success', 'added');
            return $this->redirectToRoute('city_view');
        }
        return $this->render('city/add.html.twig', [
            'cityForm' => $form
        ]);
        
    }
    #[Route('/edit/{id}', name: 'city_edit')] #edit a city fucntion
    public function editTodo($id, CityRepository $cityRepository, Request $request, ManagerRegistry $managerRegistry)
    {
        $city = $cityRepository->find($id);
        if ($city == null) {
            $this->addFlash('Error', 'Todo not found !');
            return $this->redirectToRoute('todo_index');
        }
        $form = $this->createForm(CityType::class, $city);
        //$form->add('Edit', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($city);
            $manager->flush();
            $this->addFlash('Success', 'updated');
            return $this->redirectToRoute('city_view');
        }
        return $this->render(
            'city/edit.html.twig',
            [
                'CityForm' => $form
            ]
        );
    }
    #[Route('/detail/{id}', name: 'city_detail')] #view the detail of a city function
    public function viewCityDetail($id, CityRepository $cityRepository)
    {
        $city = $cityRepository->find($id);
        if ($city == null) {
            $this->addFlash('Error', 'not found');
            return $this->redirectToRoute('city_view');
        }
        return $this->render(
            'city/detail.html.twig',
            [
                'cityForm' => $city
            ]
        );
    }
}