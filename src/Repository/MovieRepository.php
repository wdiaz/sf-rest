<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends EntityRepository
{
    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function transform(Movie $movie)
    {
        return [
            'id' => (int) $movie->getId(),
            'title' => (string) $movie->getTitle(),
            'created_at' => $movie->getCreatedAt(),
            'count' => (int) $movie->getCount(),
        ];
    }

    public function transformAll()
    {
        $movies = $this->findAll();
        $moviesArray = [];
        foreach ($movies as $movie) {
            $moviesArray[] = $this->transform($movie);
        }

        return $moviesArray;
    }

    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('m');
    }
}
