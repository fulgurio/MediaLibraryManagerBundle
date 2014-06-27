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
     */
    public function findAllWithPaginator($paginator, $page, $filter)
    {
        if (!is_null($filter) && trim($filter) != '')
        {
            $query = $this->getEntityManager()->createQuery('SELECT b FROM FulgurioMediaLibraryManagerBundle:Book b WHERE b.author LIKE :author OR b.title LIKE :title ORDER BY b.author ASC, b.title ASC');
            $query->setParameter('author', $filter . '%');
            $query->setParameter('title', $filter . '%');
        }
        else
        {
            $query = $this->getEntityManager()->createQuery('SELECT b FROM FulgurioMediaLibraryManagerBundle:Book b ORDER BY b.author ASC, b.title ASC');
        }
        return $paginator->paginate($query, $page, self::NB_PER_PAGE);
    }
}
