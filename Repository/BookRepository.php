<?php
/*
 * This file is part of the MediaLibraryManagerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\MediaLibraryManagerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends EntityRepository
{
    /**
     * Number of book per page
     * @var integer
     * @todo : put the value into config
     */
    const NB_PER_PAGE = 10;


    /**
     * Get book with pagination
     *
     * @param Paginator $paginator KNPPaginator
     * @param integer $page Current page
     * @param string $filter
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findAllWithPaginator($paginator, $page, $filter)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('FulgurioMediaLibraryManagerBundle:Book', 'b')
            ->orderBy('b.author', 'ASC')
            ->addOrderBy('b.title', 'ASC');
        if (!is_null($filter) && trim($filter) != '')
        {
            $qb->where('b.author LIKE :author')
                ->orWhere('b.title LIKE :title')
                ->setParameter('author', $filter . '%')
                ->setParameter('title', $filter . '%');
        }
        return $paginator->paginate($qb->getQuery(), $page, self::NB_PER_PAGE);
    }
}
