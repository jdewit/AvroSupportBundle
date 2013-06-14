<?php

namespace Avro\SupportBundle\Features\Context;

use Behat\Behat\Context\Step;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\Behat\Event\FeatureEvent;
use Behat\MinkExtension\Context\MinkContext;

use Symfony\Component\HttpKernel\KernelInterface;


/**
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
	protected $kernel;

	/**
	 * Sets HttpKernel instance.
	 * This method will be automatically called by Symfony2Extension ContextInitializer.
	 *
	 * @param KernelInterface $kernel
	 */
	public function setKernel(KernelInterface $kernel)
	{
		$this->kernel = $kernel;
	}

}
