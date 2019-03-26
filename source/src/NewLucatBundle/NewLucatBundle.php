<?php

namespace NewLucatBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class NewLucatBundle extends AbstractPimcoreBundle
{
    public function getNiceName()
    {
        return 'NewLucatBundle';
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDescription()
    {
        return 'Syncronize data from New LucatOpen';
    }

    public function getJsPaths()
    {
        return [
            '/bundles/newlucat/js/pimcore/startup.js'
        ];
    }
}
