<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\MigrationParameterPass;
use AppBundle\Security\PersonalizationPolicyProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension $extension */
        $extension = $container->getExtension('ezpublish');
        $extension->addPolicyProvider(new PersonalizationPolicyProvider());

        $container->addCompilerPass(new MigrationParameterPass());
    }
}
