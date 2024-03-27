<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Film::class);
    }

    public function findAllFilmsAffiche(): array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
        SELECT DISTINCT f.id, f.titre, f.duree
        FROM App\Entity\Seance s
        INNER JOIN s.film f
        WHERE s.dateProjection > CURRENT_TIMESTAMP()
        ORDER by f.id
        ');

        return $query->getResult();
    }

    public function findOneFilm(int $idFilm): array|bool {
        $entityManager = $this->getEntityManager();

        $seances = ($entityManager->createQuery('
        SELECT s.id, s.dateProjection, s.tarifNormal, s.tarifReduit, salle.nom as salle_nom, salle.nbPlaces as salle_nbPlaces
        FROM App\Entity\Seance s
        INNER JOIN s.salle salle
        WHERE s.film = :id
        AND s.dateProjection > CURRENT_TIMESTAMP()
        ORDER BY s.dateProjection ASC
        ')->setParameter('id', $idFilm))->getResult();

        if (!empty($seances)) {
            foreach ($seances as &$seance) {
                $seance['salle'] = ["nom" => $seance["salle_nom"], "nbPlaces" => $seance["salle_nbPlaces"]];
                unset($seance["salle_nom"], $seance["salle_nbPlaces"]);
            }
        } else {
            return false;
        }

        $film = ($entityManager->createQuery('
        SELECT f.id, f.titre, f.duree
        FROM App\Entity\Film f
        WHERE  f.id = :id
        ')->setParameter('id', $idFilm))->getResult();

        return ["film" => $film[0], "seances" => $seances];
    }
}