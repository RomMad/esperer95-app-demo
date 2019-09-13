<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\PeopleGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // Trouve tous les personnes du même groupe ménage
    public function findByPeopleGroup($peopleGroup) {
        
        return $this->createQueryBuilder("p")
                    ->select("p")
                    ->leftJoin("p.peopleGroups", "g")
                    ->addSelect("g")
                    ->andWhere("g = :g")
                    ->setParameter("g", $peopleGroup)
                    ->getQuery()
                    ->getResult();
    }
    
    // Trouve tous les personnes du même groupe ménage (première version)
    // public function findByPeopleGroupV1($peopleGroup){
    //     $query = $this->createQueryBuilder('p')
    //                   ->select('p')
    //                   ->leftJoin('p.peopleGroups', 'g')
    //                   ->addSelect('g');
    //     $query = $query->add('where', $query->expr()->in('g', ':g'))
    //                   ->setParameter('g', $peopleGroup)
    //                   ->getQuery()
    //                   ->getResult();
          
    //     return $query;
    // }  
}