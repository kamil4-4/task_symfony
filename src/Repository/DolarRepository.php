<?php

namespace App\Repository;

use App\Entity\Dolar;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dolar>
 *
 * @method Dolar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dolar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dolar[]    findAll()
 * @method Dolar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DolarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dolar::class);
    }

    public function findByRate(int $limit): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.date > :date')
            ->setParameter('date', ((new DateTime())->modify('- '. $limit .' days'))->format('Y-m-d'))
            ->orderBy('d.exchangeRate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
