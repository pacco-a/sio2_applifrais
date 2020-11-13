<?php

namespace App\Controller;

use App\Entity\EntreeFraisForfait;
use App\Entity\EntreeFraisHorsForfait;
use App\Entity\FicheFrais;
use App\Repository\EtatRepository;
use App\Repository\FicheFraisRepository;
use App\Repository\FraisForfaitRepository;
use App\Repository\UserRepository;
use App\Service\SessionService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function index(SessionService $sessionService, EtatRepository $etatRepository)
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

        //ROUTE UNIQUEMENT POUR VISITEURS
        if (!$sessionService->isRank(3)) {
            return $this->redirectToRoute("index");
        }

        // VERIFIER SI LA FICHE EXISTE DEJA OU NON
        $fiche = $this->ficheFraisRepository->findOneBy(["month" => intval($this->currentDate->format("m")),
            "year" => intval($this->currentDate->format("Y")),
            "idVisisteur" => $sessionService->getId()]);

        // -- si elle n'existe pas la créer
        $entityManager = $this->getDoctrine()->getManager();

        if (!$fiche) {
            $fiche = new FicheFrais();
            $fiche->setIdVisisteur($this->userRepository->find($sessionService->getId()))
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

        // USER RANK POUR LE MENU **SI** L'USER EST CONNECTE
        if ($sessionService->isLogin()) {
            $userRank = $this->userRepository->find($sessionService->getId())
                ->getRank()->getId();
        } else {
            $userRank = 0;
        }

        return $this->render('fiche/index.html.twig', [
            "isLogin" => $sessionService->isLogin(),
            "userRank" => $userRank,
            "fiche" => $fiche,
            "fraisForfaits" => $allFraisForfait,
            "fraisHorsForfaits" => $allFraisHorsForfait
        ]);
    }

    /**
     * @Route("/addfrais", name="addfrais", methods={"POST"})
     */
    public function addEntreeFrais(Request $request, SessionService $sessionService, FraisForfaitRepository $fraisForfaitRepository)
    {

        //TODO supprimer debug, tout le reste marche juste pour un test

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
            "idVisisteur" => $sessionService->getId()]);

        /**
         * SI la fiche n'existe pas rediriger vers la page "fiche"
         * ce qui va créer automatiquement une fiche.
         */

        if (!$fiche) {
            return $this->redirectToRoute("fiche");
        }


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


        if ($request->request->get("type-frais") === "forfait" && !$entreeFraisAlreadyExists) {
            $newEntreeFrais = new EntreeFraisForfait();

            // set datas
            $newEntreeFrais->setFicheFrais($fiche);
            $newEntreeFrais->setQuantity($request->request->get("quantite-frais"));


            $newEntreeFrais->setFraisForfait($fraisForfaitRepository->find(intval($request->request->get("frais-select"))));

            // -- -- validate & send
            $entityManager->persist($newEntreeFrais);
            $entityManager->flush();
        } else if ($request->request->get("type-frais") === "forfait" && $entreeFraisAlreadyExists) {

            $entreeFraisToEdit->setQuantity($entreeFraisToEdit->getQuantity() + $request->request->get("quantite-frais"));
            $entityManager->persist($entreeFraisToEdit);
            $entityManager->flush();

        } else if ($request->request->get("type-frais") === "horsforfait") {

//            dump($request->request->get("nom-frais"));
//            die();

            $newEntreeFraisHorsForfait = new EntreeFraisHorsForfait();
            $newEntreeFraisHorsForfait->setFicheFrais($fiche);
            $newEntreeFraisHorsForfait->setQuantity(intval($request->request->get("quantite-frais")));
            $newEntreeFraisHorsForfait->setPrice(intval($request->request->get("prix-frais")));
            $newEntreeFraisHorsForfait->setLibelle($request->request->get("nom-frais"));

            $entityManager->persist($newEntreeFraisHorsForfait);
            $entityManager->flush();


        }


        return $this->redirectToRoute("fiche");


    }
}
