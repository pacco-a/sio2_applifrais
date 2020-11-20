<?php

namespace App\Controller;

use App\Entity\EntreeFraisForfait;
use App\Entity\EntreeFraisHorsForfait;
use App\Entity\FicheFrais;
use App\Repository\EtatRepository;
use App\Repository\FicheFraisRepository;
use App\Repository\FraisForfaitRepository;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FicheController extends AbstractController
{
    private $currentDate;
    private $userRepository;
    private $ficheFraisRepository;

    public function __construct(UserRepository $userRepository, FicheFraisRepository $ficheFraisRepository)
    {
        $this->currentDate = new DateTime("now");
        $this->userRepository = $userRepository;
        $this->ficheFraisRepository = $ficheFraisRepository;
    }

    /**
     * @Route("/fiche", name="fiche")
     * @IsGranted("ROLE_VIS")
     */
    public function index(Security $security, EtatRepository $etatRepository)
    {

        /**
         * TODO afficher correctement la fiche sur la vue :
         *  - l'affichage des frais forfaits est bientôt fini
         *  mais autant pour les forfait que hors forfait il
         *  faudra prévoir quelque chose pour éviter qu'on puisse
         *  mettre plusieurs fois le même frais : peut-être un système
         *  pour additionner en cas de doublon OU! on enleve
         *  le choix du frais dans le form s'il existe déjà dans
         *  la fiche et on permet l'édition de la fiche = compliqué
         *  mais stylé.
         */

        // VERIFIER SI LA FICHE EXISTE DEJA OU NON
        $fiche = $this->ficheFraisRepository->findOneBy(["month" => intval($this->currentDate->format("m")),
            "year" => intval($this->currentDate->format("Y")),
            "idVisisteur" => $security->getUser()->getId()]);

        // -- si elle n'existe pas la créer
        $entityManager = $this->getDoctrine()->getManager();

        if (!$fiche) {
            $fiche = new FicheFrais();
            $fiche->setIdVisisteur($this->userRepository->find($security->getUser()->getId()))
                ->setIdEtat($etatRepository->find(1))
                ->setMonth($this->currentDate->format("m"))
                ->setNbProofs(0)
                ->setValidAmount(0)
                ->setUpdateDate($this->currentDate)
                ->setYear($this->currentDate->format("Y"));

            // -- -- validate & send
            $entityManager->persist($fiche);
            $entityManager->flush();

        }

        //une liste des objets entree frais forfaits
        //qu'on formatte un peu pour la vue.
        $allFraisForfaitObj = $fiche->getEntreeFraisForfaits()->getValues();
        $allFraisForfait = array();

        foreach ($allFraisForfaitObj as $oneFraisForfait) {
            array_push($allFraisForfait, ["quantity" => $oneFraisForfait->getQuantity(),
                "libelle" => $oneFraisForfait->getFraisForfait()->getLibelle()]);
        }

        $allFraisHorsForfaitObj = $fiche->getEntreeFraisHorsForfaits()->getValues();
        $allFraisHorsForfait = array();

        foreach ($allFraisHorsForfaitObj as $oneFraisHorsForfait) {
            array_push($allFraisHorsForfait, ["quantity" => $oneFraisHorsForfait->getQuantity(),
                "libelle" => $oneFraisHorsForfait->getLibelle(), "price" => $oneFraisHorsForfait->getPrice()]);
        }


        return $this->render('fiche/index.html.twig', [
            "isLogin" => !is_null($security->getUser()),
            "fiche" => $fiche,
            "fraisForfaits" => $allFraisForfait,
            "fraisHorsForfaits" => $allFraisHorsForfait
        ]);
    }

    /**
     * @Route("/addfrais", name="addfrais", methods={"POST"})
     * @IsGranted("ROLE_VIS")
     */
    public function addEntreeFrais(Security $security, Request $request, FraisForfaitRepository $fraisForfaitRepository)
    {
        dump("ici1");


        // dump($request->request);
        /**
         * EXEMPLE
         * [
         *  "type-frais" => "forfait"
         * "frais-select" => "1"
         * "nom-frais" => ""
         * "prix-frais" => ""
         * "quantite-frais" => "15"
         * ]
         */

        // VERIFIER SI LA FICHE EXISTE DEJA OU NON
        $fiche = $this->ficheFraisRepository->findOneBy(["month" => intval($this->currentDate->format("m")),
            "year" => intval($this->currentDate->format("Y")),
            "idVisisteur" => $security->getUser()->getId()]);

        dump($fiche);

        /**
         * SI la fiche n'existe pas rediriger vers la page "fiche"
         * ce qui va créer automatiquement une fiche.
         */

        if (!$fiche) {
            return $this->redirectToRoute("fiche");
        }

        dump("ici2");

        // CREER L'ENTREE FRAIS

        $entityManager = $this->getDoctrine()->getManager();

        /**
         * Si le frais existe déjà dans une certaine quantité,
         * additionner celle ci à la place.
         */

        $entreeFraisAlreadyExists = false;

//        dump($fiche->getEntreeFraisForfaits()->getValues());

        $entreeFraisToEdit = null;

        foreach ($fiche->getEntreeFraisForfaits()->getValues() as $entreeFrais) {

            if ($entreeFrais->getFraisForfait()->getId() == intval($request->request->get("frais-select"))) {
                $entreeFraisAlreadyExists = true;
                $entreeFraisToEdit = $entreeFrais;
                break;
            }
        }

        dump("ici3");


        if ($request->request->get("type-frais") === "forfait" && !$entreeFraisAlreadyExists) {
            $newEntreeFrais = new EntreeFraisForfait();

            // set datas
            $newEntreeFrais->setFicheFrais($fiche);
            $newEntreeFrais->setQuantity($request->request->get("quantite-frais"));


            $newEntreeFrais->setFraisForfait($fraisForfaitRepository->find(intval($request->request->get("frais-select"))));

            dump($newEntreeFrais);

            // -- -- validate & send
            $entityManager->persist($newEntreeFrais);
            $entityManager->flush();
        } else if ($request->request->get("type-frais") === "forfait" && $entreeFraisAlreadyExists) {

            $entreeFraisToEdit->setQuantity($entreeFraisToEdit->getQuantity() + $request->request->get("quantite-frais"));
            $entityManager->persist($entreeFraisToEdit);
            $entityManager->flush();


        } else if ($request->request->get("type-frais") === "horsforfait") {

            // dump($request->request->get("nom-frais"));

            $newEntreeFraisHorsForfait = new EntreeFraisHorsForfait();
            $newEntreeFraisHorsForfait->setFicheFrais($fiche);
            $newEntreeFraisHorsForfait->setQuantity(intval($request->request->get("quantite-frais")));
            $newEntreeFraisHorsForfait->setPrice(intval($request->request->get("prix-frais")));
            $newEntreeFraisHorsForfait->setLibelle($request->request->get("nom-frais"));

            $entityManager->persist($newEntreeFraisHorsForfait);
            $entityManager->flush();

        }

        dump("ici4");


        return $this->redirectToRoute("fiche");


    }


    /**
     * @Route("/validation", name="validation_page")
     * @IsGranted("ROLE_COMP")
     */
    public function getValidationPage(Request $request, UserRepository $userRepository, FicheFraisRepository $ficheFraisRepository)
    {
        //TODO coder valdiation de fiche

        $ficheChoisie = null;
        $visiteurUsername = null;

        $allFraisForfait = array();
        $allFraisHorsForfait = array();


        if ($request->query->get("moisChoice") && $request->query->get("anneeChoice") && $request->query->get("visiteur")) {

            $ficheChoisie = $ficheFraisRepository->findOneBy(["month" => $request->query->get("moisChoice"), "year" => $request->query->get("anneeChoice"), "idVisisteur" => $request->query->get("visiteur")]);
            $visiteurUsername = $userRepository->find($request->query->get("visiteur"))->getUsername();

            $allFraisForfaitObj = $ficheChoisie->getEntreeFraisForfaits()->getValues();


            foreach ($allFraisForfaitObj as $oneFraisForfait) {
                array_push($allFraisForfait, ["quantity" => $oneFraisForfait->getQuantity(),
                    "libelle" => $oneFraisForfait->getFraisForfait()->getLibelle()]);
            }

            $allFraisHorsForfaitObj = $ficheChoisie->getEntreeFraisHorsForfaits()->getValues();


            foreach ($allFraisHorsForfaitObj as $oneFraisHorsForfait) {
                array_push($allFraisHorsForfait, ["quantity" => $oneFraisHorsForfait->getQuantity(),
                    "libelle" => $oneFraisHorsForfait->getLibelle(), "price" => $oneFraisHorsForfait->getPrice()]);
            }

        }

        $visiteurs = $userRepository->findByRole("ROLE_VIS");

        return $this->render("fiche/valid.html.twig", ["ficheChoisie" => $ficheChoisie, "visiteurUsername" => $visiteurUsername, "visiteurs" => $visiteurs, "fraisForfaits" => $allFraisForfait, "fraisHorsForfaits" => $allFraisHorsForfait]);
    }

}
