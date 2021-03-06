<?php

namespace AppBundle\Repository;

/**
 * TarrifRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TarrifRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTarrifIdByLibelle($libelle)
    {

        $qb = $this->createQueryBuilder('t');
        $qb->select('t.id')
            ->where('t.libelle = :libelle')
            ->setParameter('libelle', $libelle);

        return $qb->getQuery()->getSingleResult();
    }
}
