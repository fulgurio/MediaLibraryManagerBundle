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

use Knp\FriendlyContexts\Context\MinkContext as Context;

/**
 * Defines application features from the specific context.
 */
class MinkContext extends Context
{
    /**
     * @Then I wait :arg1 second
     *
     * @param number $seconds
     */
    public function iWaitSecond($seconds)
    {
        $this->getSession()->wait(1000 * $seconds);
    }
}
