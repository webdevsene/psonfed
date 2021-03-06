<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Form\Dossier1Type;
use App\Form\SearchDossierType;
use App\Repository\DossierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/dossier")
 */
class DossierController extends AbstractController
{
    /**
     * @Route("/", name="dossier_index", methods={"GET", "POST"})
     */
    public function index(DossierRepository $dossierRepository, Request $request): Response
    {

        #$dossier = $dossierRepository->findBy(['active' => true], ['created_at' => "desc"], 10);
        $dossier = $dossierRepository->findAll();

        $form = $this->createForm(SearchDossierType::class);

        $search = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # code... recherche les dossiers correspondant aux mots clés
            $dossier = $dossierRepository->search($search->get('mots')->getData());
        }

        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossier,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="dossier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dossier = new Dossier();
        $form = $this->createForm(Dossier1Type::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dossier);
            $entityManager->flush();

            return $this->redirectToRoute('dossier_index');
        }

        return $this->render('dossier/new.html.twig', [
            'dossier' => $dossier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dossier_show", methods={"GET"})
     */
    public function show(Dossier $dossier): Response
    {
        return $this->render('dossier/show.html.twig', [
            'dossier' => $dossier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dossier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Dossier $dossier): Response
    {
        $form = $this->createForm(Dossier1Type::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dossier_index');
        }

        return $this->render('dossier/edit.html.twig', [
            'dossier' => $dossier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dossier_delete", methods={"POST"})
     */
    public function delete(Request $request, Dossier $dossier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dossier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dossier_index');
    }

    /**
     * Search action method for Live earch feature.
     * Creates a new ActionItem entity
     * 
     * @Route("/search", name="doss_search")
     */
    public function searchAction(DossierRepository $dr,  Request $request): Response
    {
        //$dossier = $dr->findAll();

        $query = $request->query->get('searchField', ''); // on recupere le input $_GET
        $limit = $request->query->get('l', 10);

        if (!$request->isXmlHttpRequest()) {
            return $this->render('dossier/search.html.twig', ['query' => $query]);
        }

        // $foundDossiers = $dr->search($request->get('searchField')->getData());
        $foundDossiers = $dr->findBySearchQuery($request, $limit);

        //$foundDossiers = $dr->search($query);

        $results = [];

        foreach ($foundDossiers as $dossier) {
            $results[] = [
                'cote' => htmlspecialchars($dossier->getCote(), \ENT_COMPAT | \ENT_HTML5),
                'titre' => htmlspecialchars($dossier->getTitre(), \ENT_COMPAT | \ENT_HTML5),
                'analyse' => htmlspecialchars($dossier->getAnalyse(), \ENT_COMPAT | \ENT_HTML5),
                'date_debut' => $dossier->getDateDebut()->format('M d, Y'),
                'date_butoire' => $dossier->getDateButoire()->format('M d, Y'),
            ];
        }

        return $this->json($results);
    } 

}
