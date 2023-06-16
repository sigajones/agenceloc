<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\UserRepository;
use App\Repository\CommandeRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/gestion/vehicule', name: 'gestion_vehicule')]
    public function gestionVehicule(VehiculeRepository $repo, EntityManagerInterface $manager): Response
    {
        $colonnes = $manager->getClassMetadata(Vehicule::class)->getFieldNames();

        $vehicules = $repo->findAll();

        return $this->render('admin/gestionVehicule.html.twig', [
            "colonnes" => $colonnes,
            "vehicules" => $vehicules
        ]);
    }

    #[Route('/admin/vehicule/new', name: 'new_vehicule')]
    #[Route('/admin/vehicule/edit/{id}', name: 'edit_vehicule')]
    public function formVehicule(Request $request, EntityManagerInterface $manager, Vehicule $vehicule = null)
    {
        if(!$vehicule)
        {

            $vehicule = new Vehicule;
            $vehicule->setDateEnregistrement(new \DateTime());
        }
        $form = $this->createForm(VehiculeType::class, $vehicule);
        
        $form->handleRequest($request);
        // dd($vehicule);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($vehicule);
            $manager->flush();
            $this->addFlash('success', "Le véhicule a bien été enregistré");
            return $this->redirectToRoute('gestion_vehicule', [
                'id' => $vehicule->getId()
            ]);
        }

        return $this->render('admin/ajoutVehicule.html.twig', [
            'formVehicule' => $form->createView(),
            'editMode' => $vehicule->getId() !== NULL
        ]);
    }

    #[Route('/admin/vehicule/delete/{id}', name:'delete_vehicule')]
    public function deleteVehicule(Vehicule $vehicule, EntityManagerInterface $manager)
    {
        $manager->remove($vehicule);
        $manager->flush();
        $this->addFlash('success', "Le vehicule a bien été supprimé");
        return $this->redirectToRoute('gestion_vehicule');
    }

    #[Route('/admin/gestion/user', name: 'gestion_user')]
    public function gestionUser(UserRepository $repo, EntityManagerInterface $manager): Response
    {
        $colonnes = $manager->getClassMetadata(User::class)->getFieldNames();

        $users = $repo->findAll();

        return $this->render('admin/gestionUser.html.twig', [
            "colonnes" => $colonnes,
            "users" => $users
        ]);
    }

    #[Route('/admin/gestion/order', name: 'gestion_order')]
    public function gestionOrder(CommandeRepository $repo, EntityManagerInterface $manager): Response
    {
        $colonnes = $manager->getClassMetadata(Commande::class)->getFieldNames();

        $commandes = $repo->findAll();
        return $this->render('admin/gestionOrder.html.twig', [
            "colonnes" => $colonnes,
            "commandes" => $commandes
        ]);
    }
}
