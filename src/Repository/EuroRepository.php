<?php

namespace App\Repository;

use App\Entity\Euro;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Euro>
 *
 * @method Euro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Euro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Euro[]    findAll()
 * @method Euro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EuroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Euro::class);
    }

    public function findByRate(int $limit): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.date > :date')
            ->setParameter('date', ((new DateTime())->modify('- '. $limit .' days'))->format('Y-m-d'))
            ->orderBy('e.exchangeRate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
