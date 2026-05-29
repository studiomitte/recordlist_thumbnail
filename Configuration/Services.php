<?php

declare(strict_types=1);

namespace StudioMitte\RecordlistThumbnail;

use StudioMitte\RecordlistThumbnail\EventListener\AppendThumbnailToRecordRow;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Information\Typo3Version;

return static function (ContainerConfigurator $configurator, ContainerBuilder $containerBuilder): void {
    // The AfterRecordListRowPreparedEvent only exists on TYPO3 v14+. On older
    // versions the listener class references types (e.g. TcaSchemaFactory) that
    // are not available, which would break autowiring. Register it manually
    // and only when running on a supported version.
    if ((new Typo3Version())->getMajorVersion() < 14) {
        return;
    }

    $services = $configurator->services();
    $services->set(AppendThumbnailToRecordRow::class)
        ->autowire()
        ->autoconfigure();
};
