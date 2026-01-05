<?php

use Bazinga\Bundle\JsTranslationBundle\Command\DumpCommand;
use Bazinga\Bundle\JsTranslationBundle\Dumper\TranslationDumper;
use Bazinga\Bundle\JsTranslationBundle\Finder\TranslationFinder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('bazinga.jstranslation.translation_finder.class', TranslationFinder::class)
        ->set('bazinga.jstranslation.translation_dumper.class', TranslationDumper::class);

    $services = $container->services();

    $services->set('bazinga.jstranslation.translation_finder', param('bazinga.jstranslation.translation_finder.class'))
        ->public()
        ->args([
            [], // all resource files paths from the framework bundle
        ]);

    $services->set('bazinga.jstranslation.translation_dumper', param('bazinga.jstranslation.translation_dumper.class'))
        ->public()
        ->args([
            service('twig'),
            service('bazinga.jstranslation.translation_finder'),
            service('filesystem'),
            null, // fallback (locale)
            null, // default domain
            null, // active locales
            null, // active domains
        ]);

    $services->set('bazinga.jstranslation.dump_command', DumpCommand::class)
        ->public()
        ->args([
            service('bazinga.jstranslation.translation_dumper'),
            param('kernel.project_dir'),
        ])
        ->tag('console.command', ['command' => 'bazinga:js-translation:dump']);
};