<?php
/*
 * This file is part of the MediaLibraryManagerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\MediaLibraryManagerBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Provides hooks for building and cleaning up a database schema with Doctrine.
 *
 * While building the schema it takes all the entity metadata known to Doctrine.
 *
 * @author Jakub Zalas <jakub@zalas.pl>
 */
class SymfonyDoctrineContext implements Context, KernelAwareContext {
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    private $kernel = null;

    /**
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $event
     *
     * @BeforeScenario @database
     */
    public function buildSchema(BeforeScenarioScope $event) {
//        echo __METHOD__, PHP_EOL;
        foreach ($this->getEntityManagers() as $entityManager) {
            $metadata = $this->getMetadata($entityManager);
            if (!empty($metadata)) {
                $tool = new SchemaTool($entityManager);
                $tool->dropSchema($metadata);
                $tool->createSchema($metadata);
            }
        }
    }

    /**
     * @param \Behat\Behat\Hook\Scope\AfterScenarioScope $event
     *
     * @AfterScenario @database
     *
     * @return null
     */
    public function closeDBALConnections(AfterScenarioScope $event) {
//        echo __METHOD__, PHP_EOL;
//        /** @var EntityManager $entityManager */
//        foreach ($this->getEntityManagers() as $entityManager)
//        {
//            $entityManager->clear();
//        }
//
//        /** @var Connection $connection */
//        foreach ($this->getConnections() as $connection)
//        {
//            $connection->close();
//        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     *
     * @return null
     */
    public function setKernel(KernelInterface $kernel) {
        $this->kernel = $kernel;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return array
     */
    protected function getMetadata(EntityManager $entityManager) {
        return $entityManager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @return array
     */
    protected function getEntityManagers() {
        return $this->kernel->getContainer()->get('doctrine')->getManagers();
    }

    /**
     * @return array
     */
    protected function getConnections() {
        return $this->kernel->getContainer()->get('doctrine')->getConnections();
    }
}
