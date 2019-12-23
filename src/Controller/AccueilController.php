<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(PersonneRepository $personneRepository)
    {
        $personnageJedi = $personneRepository->findby(["faction" => "Jedi"]);
        $personnageSith = $personneRepository->findby(["faction" => "Sith"]);

        return $this->render('accueil/index.html.twig', [
            "personnageJedi" => $personnageJedi,
            "personnageSith" => $personnageSith,
        ]);
    }

        /**
     * @Route("/listePerso", name="listePerso")
     */
    public function listePerso(PersonneRepository $personneRepository, PaginatorInterface $paginatorInterface, Request $request)
    {
        $personnage = $personneRepository->findAll();

        $personnage = $paginatorInterface->paginate(
            $personneRepository->findAllWithPagination(),
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('accueil/listePerso.html.twig', [
            "personnage" => $personnage,
        ]);
    }

            /**
     * @Route("/listePerso/personnage/creation", name="inscription")
     * @Route("/listePerso/personnage/{id}", name="modification", methods="GET|POST")
     */
    public function inscription(Personne $personnage = null, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        if (!$personnage) {
            $personnage = new Personne;
        }
        
        $form = $this->createForm(PersonneType::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modif = $personnage->getId() !== null;
            $entityManagerInterface->persist($personnage);
            $entityManagerInterface->flush();
            $this->addFlash("success", ($modif) ? "La modification a été effectuée" : "L'ajout a été effetuée");
            return $this->redirectToRoute("listePerso");
        }

        return $this->render('accueil/inscription.html.twig', [
            "personnage" => $personnage,
            "form"=>$form->createView(),
            "isModification" => $personnage->getId() !== null,
        ]);
    }

               /**
     * @Route("/listePerso/personnage/{id}", name="suppPersonnage", methods="SUP")
     */
    public function suppression(Personne $personnage, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        if ($this->isCsrfTokenValid("SUP".$personnage->getId(), $request->get("_token"))) {
            $entityManagerInterface->remove($personnage);
            $entityManagerInterface->flush();
            $this->addFlash('success', "La suppression a été effectué");
            return $this->redirectToRoute("listePerso");
        }
    }
}
