<?php

namespace AppBundle\Controller;

use AppBundle\Website\Controller\DynamicPage;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use \Pimcore\Model\DataObject;

/**
* Events Controller
*
* @package LUSEM
* @category AppBundle
* @author Jonas Ledendal <Jonas.Ledendal@har.lu.se>, M. Ali
* @version 2.0
*/
class EventsController extends DynamicPage
{
    /**
    * Events Detail Action
    */
    public function detailAction(Request $request)
    {
        try {
            // Get current language
            $language = $this->language;

            // Get params from the request/url
            $page = $request->get('page');
            $key = $request->get('key');

            // Get events object by key
            $events = new DataObject\Events\Listing();
            $events->setCondition("o_key = " . $events->quote($key));
            $events->load();
            $event = $events->getObjects()[0];

            // Get lokal object by id
            $lokal = DataObject\Lokal::getById($event->getVenue());

            if($event)
            {
                // Assign event object to view
                $this->view->title = $event->getRubrik();
                $this->view->editHeadTitle = true;
                $this->view->event = $event;
                $this->view->google = \Pimcore\Config::getSystemConfig()->services->google;
                if ($lokal) {
                    $this->view->lokal = $lokal;
                    $this->view->coordinate = $lokal->getLatitud() . ',' . $lokal->getLongitud();
                }
                $this->view->breadcrumbs = $event;

                // Assign header social media meta tags
                try
                {
                    if($event->getImage1() !== NULL) {
                        $image = 'https://' . $_SERVER['HTTP_HOST'] . $event->getImage1('Opengraph');
                    }
                    else {
                        $logoDomain = ($language == 'sv') ? 'ehl' : 'lusem';
                        $image = 'https://' . $_SERVER['HTTP_HOST'] . '/static/toolkit/images/logo/logo_' . $logoDomain . '@2x' . '.png';
                    }

                    // assign twitter metas
                    $this->view->twitter = [
                        'card'          => 'summary',
                        'site'          => $this->config->twitter_site,
                        // 'title'         => $event->getRubrik(),
                        // 'description'   => substr($event->getIngress(), 0,200),
                        // 'image:src'     => $image,
                        'creator'       => $this->config->twitter_site
                    ];

                    // assign open graph facebook metas
                    $this->view->opengraph = [
                        'og:title'       => $event->getRubrik(),
                        'og:description' => $event->getIngress(),
                        'og:type'        => 'article',
                        'og:url'         => 'https://' . $_SERVER['HTTP_HOST'] . $this->document->getFullPath() . '/' . $id,
                        'og:image'       => $image,
                        'og:site_name'   => $this->website['name']
                    ];
                }
                catch(\Exception $e) {
                    // Write a log to debug.log
                    \Pimcore\Log\Simple::log('debug', $e->getMessage() . ' ' . __FILE__ . " Line: " . __LINE__);

                    if($this->debugmode) {
                        throw new \Exception($e->getMessage());
                    }
                }
            }
        }
        catch(\Exception $e) {
            $event = null;
            // Write a log to debug.log
            \Pimcore\Log\Simple::log('debug', $e->getMessage() . ' ' . __FILE__ . " Line: " . __LINE__);

            throw new \Exception($e->getMessage());
        }
    }

    /**
    * Events Preview Action
    */
    public function previewAction(Request $request)
    {
        try {
            // Get id from the request/url
            $id = $request->get('id');

            // Get event object by id
            $event = DataObject\Events::getById($id);

            // Get lokal object by id
            $lokal = DataObject\Lokal::getById($event->getVenue());

            if($event)
            {
                // Assign event object to view
                $this->view->event = $event;
                $this->view->google = \Pimcore\Config::getSystemConfig()->services->google;
                if ($lokal) {
                    $this->view->lokal = $lokal;
                    $this->view->coordinate = $lokal->getLatitud() . ',' . $lokal->getLongitud();
                }
            }
        }
        catch(\Exception $e) {
            $event = null;
        }
    }
}
