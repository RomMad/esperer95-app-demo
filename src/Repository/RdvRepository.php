<?php

namespace App\Repository;

use App\Entity\Rdv;
use Doctrine\ORM\Query;
use App\Entity\SupportGroup;
use App\Security\CurrentUserService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Rdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rdv[]    findAll()
 * @method Rdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RdvRepository extends ServiceEntityRepository
{
    private $currentUserService;

    public function __construct(ManagerRegistry $registry, CurrentUserService $currentUserService)
    {
        parent::__construct($registry, Rdv::class);

        $this->currentUserService = $currentUserService;
    }

    /**
     * Return all rdvs of group support
     * 
     * @return Query
     */
    public function findAllRdvsQuery($supportGroupId, $rdvSearch): Query
    {
        $query =  $this->createQueryBuilder("r")
            ->select("r")
            ->leftJoin("r.createdBy", "u")
            ->addselect("u")
            ->leftJoin("r.supportGroup", "s")
            ->addSelect("s")
            ->andWhere("s.id = :supportGroup")
            ->setParameter("supportGroup", $supportGroupId);

        if ($rdvSearch->getContent()) {
            $query->andWhere("r.content LIKE :content")
                ->setParameter("content", '%' . $rdvSearch->getContent() . '%');
        }
        if ($rdvSearch->getStatus()) {
            $query->andWhere("r.status = :status")
                ->setParameter("status", $rdvSearch->getStatus());
        }

        return  $query->orderBy("r.createdAt", "DESC")
            ->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);
    }

    /**
     * Trouve tous les RDV entre 2 dates
     * 
     * @return Rdv[]
     */
    public function findRdvsBetween(\Datetime $start, \Datetime $end, SupportGroup $supportGroup = null)
    {
        $query = $this->createQueryBuilder("r")
            ->select("r")
            ->leftJoin("r.createdBy", "u")
            ->addselect("u")
            ->leftJoin("r.supportGroup", "s")
            ->addselect("s")
            ->andWhere("r.start >= :start")
            ->setParameter("start", $start)
            ->andWhere("r.start <= :end")
            ->setParameter("end", $end)
            ->andWhere("r.createdBy = :user")
            ->setParameter("user",  $this->currentUserService->getUser());

        if ($supportGroup) {
            $query->andWhere("r.supportGroup = :supportGroup")
                ->setParameter("supportGroup",  $supportGroup);
        }

        return $query->orderBy("r.start", "ASC")
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();
    }

    /**
     * Donne tous les RDV entre 2 dates par jour
     * 
     * @return Array
     */
    public function FindRdvsBetweenByDay(\Datetime $start, \Datetime $end, $supportGroup): array
    {
        $rdvs = $this->findRdvsBetween($start, $end, $supportGroup);
        $days = [];

        foreach ($rdvs as $rdv) {
            $date =  $rdv->getStart()->format("Y-m-d");
            if (!isset($days[$date])) {
                $days[] = $date;
            }
            $days[$date][] = $rdv;
        }
        return $days;
    }
}