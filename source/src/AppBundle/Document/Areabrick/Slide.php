<?php

namespace AppBundle\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class Slide extends AbstractTemplateAreabrick
{
    /**
     * @inheritdoc
     */
    public function getTemplateLocation()
    {
        return static::TEMPLATE_LOCATION_GLOBAL;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Slide';
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * @inheritdoc
     */
    public function getIcon()
    {
        return '/bundles/pimcoreadmin/img/icon/picture.png';
    }

    public function hasEditTemplate()
    {
        return true;
    }
}
