<?php

namespace AppBundle\Controller;

use AppBundle\Website\Controller\DynamicPage;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use \Pimcore\Model\Element\Tag;
use \Pimcore\Model\DataObject;

/**
* News Controller
*
* @package LUSEM
* @category AppBundle
* @author Jonas Ledendal <Jonas.Ledendal@har.lu.se>, M. Ali
* @version 2.0
*/
class NewsController extends DynamicPage
{
    /**
    * News Detail Action
    */
    public function detailAction(Request $request, $validateForFrontend = TRUE)
    {
        try
        {
            // setup conditions - we will need them to know what news to load
            $paramId = explode('/', $request->get('id'));
            $id = is_array($paramId) ? $paramId[0] : $paramId;
            $language = $this->language;
            $condition = is_numeric($id) ? "o_id = ".$id : "o_key = '".$id."'";
            // create news list
            $list = new DataObject\News\Listing();
            if($validateForFrontend) {
                // $language = sv/en 
                $condition .= " AND " . strtoupper($language) . " = 1";
            }
            else {

                $list->setUnpublished(true);
            }

            // set conditions from earlier and load news
            $list->setCondition($condition);
            $results = $list->load();
            $object = $results[0];

            if($object) {
                // checking for valid subdomain or webfilter
                if($validateForFrontend) {
                    $webb = is_array($object->getWebb()) ? $object->getWebb() : array();
                    $validWebb = array_filter($webb, function($w) {
                        return ($w == $this->website['subdomain'] || strstr($this->website['newsFilter'], $w));
                    });
                    if( !$validWebb) {

                        throw new \Exception('News object not found. ');
                    }
                }

                $tags = Tag::getTagsForElement($object->getType(), $object->getId());

                //assign news object to view
                $this->view->title = $object->getRubrik();
                $this->view->editHeadTitle = true;
                $this->view->breadcrumbs = $object;
                $this->view->nyheter = $object;
                $this->view->tags = $tags;
                $this->view->news_locale = ($object->getRubrik($language)) ? $language : FALSE;

                // assign header social media meta tags
                try
                {
                    if($object->getImage1() !== NULL) {
                        $image = 'https://' . $_SERVER['HTTP_HOST'] . $object->getImage1('Opengraph');
                    }
                    else {
                        $logoDomain = ($language == 'sv') ? 'ehl' : 'lusem';
                        $image = 'https://' . $_SERVER['HTTP_HOST'] . '/static/toolkit/images/logo/logo_' . $logoDomain . '@2x' . '.png';
                    }

                    // assign twitter metas
                    $this->view->twitter = [
                        'card'          => 'summary',
                        'site'          => $this->config->twitter_site,
                        // 'title'         => $object->getRubrik(),
                        // 'description'   => substr($object->getIngress(), 0,200),
                        // 'image:src'     => $image,
                        'creator'       => $this->config->twitter_site
                    ];

                    // assign open graph facebook metas
                    $this->view->opengraph = [
                        'og:title'       => $object->getRubrik(),
                        'og:description' => $object->getIngress(),
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
            else {
                throw new \Exception('News object not found.');
            }
        }
        catch(\Exception $e) {
            // ops! something went terribly wrong
            $object = null;
            // Write a log to debug.log
            \Pimcore\Log\Simple::log('debug', $e->getMessage() . ' ' . __FILE__ . " Line: " . __LINE__);

            throw new \Exception($e->getMessage());
        }
    }

    /**
    * News Preview Action
    */
    public function previewAction(Request $request)
    {
        // Language
        $language = $this->language;

        // Get id from the request/url
        $id = $request->get('id');

        // Get news object by id
        $news = DataObject\News::getById($id);

        // Assign news object to view
        $this->view->title = $news->getRubrik();
        $this->view->nyheter = $news;
        $this->view->tags = $tags;
        $this->view->news_locale = ($news->getRubrik($language)) ? $language : FALSE;
    }

}
