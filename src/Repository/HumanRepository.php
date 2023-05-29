<?php

namespace App\Repository;

use App\Entity\Human;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Human>
 *
 * @method Human|null find($id, $lockMode = null, $lockVersion = null)
 * @method Human|null findOneBy(array $criteria, array $orderBy = null)
 * @method Human[]    findAll()
 * @method Human[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HumanRepository extends ServiceEntityRepository
{
    public const PER_PAGE = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Human::class);
    }

    public function paginateByRequest(Request $request): Paginator
    {
        $perPage = max($request->query->getInt('per_page', self::PER_PAGE), 1);

        $queryBuilder = $this->createQueryBuilder('h');
        $queryBuilder
            ->select('h.uuid', 'h.name')
            ->orderBy(
                sprintf('h.%s', $request->query->get('sort_by', 'id')),
                $request->query->get('sort_dir', 'asc')
            )
            ->setMaxResults($perPage)
            ->setFirstResult(max($request->query->getInt('page', 0) * $perPage, 0))
        ;

        return new Paginator($queryBuilder->getQuery(), false);
    }

    public function save(Human $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Human $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
