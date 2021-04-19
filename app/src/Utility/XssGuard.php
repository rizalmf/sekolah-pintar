<?php

namespace app\src\Utility;

use voku\helper\AntiXSS;

class XssGuard
{
    private $antiXss;

    public function __construct($exlude_style = false) {
        $this->antiXss = new AntiXSS();
        if ($exlude_style) {
            // perbolehkan tag styling
            $this->exclude([
                'attributes' => array('style')
            ]);
        }
    }

    /**
     * clean xss
     * 
     * @param array|string $rawData
     * @return array|string clean
     */
    public function clean($rawData)
    {
        return $this->antiXss->xss_clean($rawData);
    }

    /**
     * check xss is found or not
     * 
     * @return boolean isXss Found
     */
    public function isEvil()
    {
        return $this->antiXss->isXssFound();
    }

    /**
     * Exclude specific xss suspect
     * 
     * @param array $suspect with accepted keys: attributes, tags, events
     * @return void 
     */
    public function exclude($suspect)
    {
        if (isset($suspect['attributes'])) {
            $this->antiXss->removeEvilAttributes($suspect['attributes']);
        }
        if (isset($suspect['tags'])) {
            $this->antiXss->removeEvilHtmlTags($suspect['tags']);
        }
        if (isset($suspect['events'])) {
            $this->antiXss->removeNeverAllowedOnEventsAfterwards($suspect['events']);
        }
    }

}