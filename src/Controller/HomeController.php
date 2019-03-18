<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Car;
use App\Entity\Keyword;
use App\Repository\CarRepository;
use App\Form\CarType;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(CarRepository $carRepo)
    {
      $cars = $carRepo->findAll();


        return $this->render('home/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    /**
    *
    * @Route("/show/{id}", name="show")
    */
    public function showLivre(Car $car)
    {
      return $this->render('home/show.html.twig', [
        'car' => $car
      ]);
    }

    /**
    *
    * @Route("/symfony/contact", name="contact")
    */
    public function contact()
    {
      return $this->render('home/contact.html.twig');
    }

    /**
    *
    * @Route("/add", name="add")
    */
    public function add(EntityManagerInterface $manager, Request $request)
    {

        $form = $this->createForm(CarType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $path =  $this->getParameter('kernel.project_dir').'/public/images';
            // On récupère les données soumises
            $car = $form->getData();

            // On récupère l'objet Image depuis l'objet Car
            $image = $car->getImage();

            // On récupère le file de l'objet image
            $file = $image->getFile();

            // On définit un nom aléatoire
            $name = md5(uniqid()).'.'.$file->guessExtension();

            // On déplace l'image dans le dossier
            $file->move($path, $name);

            // On lui donne le nouveau nom à l'image
            $image->setName($name);

            $manager->persist($car);
            $manager->flush();

            $this->addFlash(
                'notice',
                'La voiture a bien été enregistrer !'
            );
            return $this->redirectToRoute('home');
        }


        return $this->render('home/add.html.twig', [
          'form' => $form->createView(),
        ]);
    }

    /**
    *
    * @Route("/edit/{id}", name="edit")
    */
    public function edit(Car $car, EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form-> isValid()){

          $manager->flush();

          $this->addFlash(
              'notice',
              'La voiture modifiée!'
          );
          return $this->redirectToRoute('home');
        }

        return $this->render('home/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    /**
    *
    * @Route("/delete/{id}", name="delete")
    */
    public function delete(Car $car, EntityManagerInterface $manager)
    {
        $manager->remove($car);
        $manager->flush();

        return $this->redirectToRoute('home');
    }
}
