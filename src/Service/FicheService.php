<?php


namespace App\Service;


use App\Entity\FicheFrais;
use DateTime;
use Doctrine\ORM\EntityManager;

class FicheService
{
    private $currentDate;

    public function __construct()
    {
        $this->currentDate = new DateTime("now");
    }


    /**
     * RENVOI true si l'on est sensé pouvoir écrire sur
     * la fiche, false sinon.
     */
    public function isWritable(FicheFrais $ficheFrais, EntityManager $entityManager) : bool
    {
        /**
         * SI la fiche n'est plus courante et qu'elle est encore en éditable,
         * ALORS  la mettre en cloturée.
         */
        if(intval($ficheFrais->getMonth()) != intval($this->currentDate->format("m")) || intval($ficheFrais->getYear()) != intval($this->currentDate->format("o"))) {
            if(intval($ficheFrais->getIdEtat()) == 1 ) {
                $ficheFrais->setIdEtat(2);
                $entityManager->flush();
            }
        }


        return intval($ficheFrais->getIdEtat()->getId()) == 1;
    }

}