<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Collaborateur;
use AppBundle\Entity\Dossier;
use AppBundle\Entity\Tache;
use AppBundle\Entity\Temps;
use AppBundle\Form\Recap2Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Recap1Type;

class DefaultController extends Controller
{
    public function indexAction()
    {        
        return $this->render('index.html.twig');
    }
    
    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function listDossierAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Dossier::class);
        $listDossierQuery = $repository->findAll();
                
        return $this->render('lists/dossier.html.twig', array('listDossier' => $listDossierQuery));
             
    }
    
    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function listTacheAction()
    {        
        $repository = $this->getDoctrine()->getManager()->getRepository(Tache::class);
        $listTacheQuery = $repository->findAll();

        return $this->render('lists/tache.html.twig', array('listTache' => $listTacheQuery));
    }

    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function listTempsAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Temps::class);
        $listTempsQuery = $repository->findAll();

        return $this->render('lists/temps.html.twig', array('listTemps' => $listTempsQuery));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listTempsCollaborateurAction()
    {
        $collaborateur = $this->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Temps');
        $listTempsQuery = $repository->findByCollaborateur($collaborateur);

        return $this->render('lists/temps.html.twig', array('listTemps' => $listTempsQuery));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listToutTempsCollaborateurAction()
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $collaborateur = $this->get('security.token_storage')->getToken()->getUser();
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Temps');
            $listTempsQuery = $repository->findByCollaborateur($collaborateur);
        }
        else{
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Temps');
            $listTempsQuery = $repository->findAll();
        }


        return $this->render('lists/touttemps.html.twig', array('listTemps' => $listTempsQuery));
    }
    
    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function RecapAction(Request $request)
    {
        /* there are 2 filters available, each one is a form: recap1Type, recap2Type */

        $repoTemps = $this->getDoctrine()->getManager()->getRepository('AppBundle:Temps');

        $form1 = $this->createForm(Recap1Type::class);
        $form1->handleRequest($request);

        $form2 = $this->createForm(Recap2Type::class);
        $form2->handleRequest($request);

        /*filter 1 : show total Tempspassé for a Dossier, a Exercice and for each Tache */
                
        if ($form1->isSubmitted() && $form1->isValid()) {
            $data = $form1->getData();
            
            $idDossier = $data["dossier"]->getId();
            $dossier = $data["dossier"];
            $exercice = $data["exercice"];
            $dateDebut = $data["date_debut"];
            $dateFin = $data["date_fin"];
            $forever = $data["forever"];

            $results = $repoTemps->getSumByDossierExercice($idDossier, $exercice, $dateDebut, $dateFin, $forever);

            $details = $repoTemps->getByDossierExercice($idDossier, $exercice, $dateDebut, $dateFin, $forever);

           return $this->render('results.html.twig',
               array('dossier' => $dossier, 'exercice' => $exercice,
                   'dateDebut' => $dateDebut, 'dateFin' => $dateFin,
                   'results' => $results, 'details' => $details
               ));
        }

        /*filter 2 : show total Tempspassé for a Exercice, a Collaborateur and for each Dossier */

        elseif ($form2->isSubmitted() && $form2->isValid()) {
            $data = $form2->getData();

            $idCol = $data["collaborateur"]->getId();
            $col = $data["collaborateur"];
            $exercice = $data["exercice"];
            $dateDebut = $data["date_debut"];
            $dateFin = $data["date_fin"];
            $forever = $data["forever"];

            $results2 = $repoTemps->getSumByColExercice($idCol, $exercice, $dateDebut, $dateFin, $forever);

            $details2 = $repoTemps->getByColExercice($idCol, $exercice, $dateDebut, $dateFin, $forever);


            return $this->render('results.html.twig',
                array('collaborateur' => $col, 'exercice' => $exercice,
                    'dateDebut' => $dateDebut, 'dateFin' => $dateFin,
                    'results2' => $results2, 'details' => $details2));
        }

        else
        {
            return $this->render('recap.html.twig', array('form1' => $form1->createView(), 'form2' => $form2->createView()));
        }
     
    }
    
}