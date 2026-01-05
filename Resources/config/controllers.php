<?php

use Bazinga\Bundle\JsTranslationBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('bazinga.jstranslation.controller.class', Controller::class);

    $services = $container->services();

    $services->set('bazinga.jstranslation.controller', param('bazinga.jstranslation.controller.class'))
        ->public()
        ->args([
            service('translator'),
            service('twig'),
            service('bazinga.jstranslation.translation_finder'),
            param('kernel.cache_dir') . '/bazinga-js-translation',
            param('kernel.debug'),
            null, // fallback (locale)
            null, // default domain
            null, // http cache time
        ]);
};