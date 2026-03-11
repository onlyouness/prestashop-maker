<?php

declare(strict_types=1);

namespace {{namespace}}\Repository;

use Doctrine\ORM\EntityRepository;

class {{entity_name}}Repository extends EntityRepository
{
    // public function findAllByLang(int $idLang)
    // {
    //     try {
    //         $query = $this->createQueryBuilder('b')
    //             ->select('b.*')
    //             ->innerJoin('b.translations', 'bl')
    //             ->where('bl.lang = :idLang')
    //             ->setParameter('idLang', $idLang)
    //             ->getQuery();
    //             // dd($query);
    //         return $query->getResult();
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    // }
}
