<?php


/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @link    https://contao.org
 *
 *
 * PHP version 5
 * @copyright  Nothing Interactive 2014
 * @author        Yanick Witschi <yanick.witschi@terminal42.ch>
 * @license      commercial
 */
class GeoDomains extends Frontend
{
    // Enable/Disable debug mode
    const VERBOSE = FALSE;

    /**
     * Debug helper, if in verbose mode adds prints to the system log about the redirecting for geo domains.
     *
     * @param $message
     * @param $sourceMethod
     */
    private function debug($message, $sourceMethod)
    {
        if ($this::VERBOSE) {
            $this->log(
                $message,
                __CLASS__ . ' ' . $sourceMethod . '()',
                TL_INFO
            );
        }
    }


    /**
     * Replace Contao inserttags about the current domain
     *
     * @param string
     *
     * @return string|false
     */
    public function replaceTags($strTag)
    {
        $arrTag = trimsplit('::', $strTag);

        if ($arrTag[0] == 'gd') {
            global $objPage;
            $objRootPage = $this->Database->execute("SELECT * FROM tl_page WHERE id=" . (int)$objPage->rootId);

            switch ($arrTag[1]) {
            case 'region_name':
                return $objRootPage->gd_region_name ? $objRootPage->gd_region_name : ($objRootPage->pageTitle ? $objRootPage->pageTitle : $objRootPage->title);

            case 'city_name':
                return $objRootPage->gd_city_name ? $objRootPage->gd_city_name : ($objRootPage->pageTitle ? $objRootPage->pageTitle : $objRootPage->title);
            }

            return '';
        }

        return false;
    }


    /**
     * Redirects the visitor based on an ip range database
     * @return Database_Result root page db result
     */
    public function detectRootPage()
    {
        // Redirect disabled
        if (!$GLOBALS['TL_CONFIG']['addLanguageToUrl'] || $GLOBALS['TL_CONFIG']['doNotRedirectEmpty']) {
            $this->debug(__CLASS__ . ' not redirecting, reason: doNotRedirectEmpty.', __METHOD__);
            return false;
        }

        $strRequest = ampersand(\Environment::get('indexFreeRequest'), true);
        //preg_replace(array('/^index.php\/?/', '/\?.*$/'), '', $this->Environment->request);

        $arrFragments = explode('/', $strRequest);

        // Points to a specific page don't redirect
        if (preg_match('/^[a-z]{2}$/', $arrFragments[0])) {
            $this->debug(__CLASS__ . ' not redirecting, reason: page specified.', __METHOD__);
            return false;
        }

        // load the current page root object
        $objRootPage = $this->getDefault();

        if (!$objRootPage) {
            $this->debug(__CLASS__ . ' not redirecting, reason: root of current page not found.', __METHOD__);
            return false;
        }

        $objRootPage = $this->Database->prepare('SELECT * FROM tl_page WHERE id=?')->execute($objRootPage->id);

        // if the page is the GeoDomain fallback, we check if we can get any better - otherwise it's just the root page
        if (!$objRootPage->gd_fallback) {
            $this->debug(__CLASS__ . ' not redirecting, reason: domain specified (not fallback)', __METHOD__);
            return false;
        }

        $country = $this->findCountry();

        // if no matching country is found we stay on the fallback
        if (!$country) {
            $this->debug(__CLASS__ . ' not redirecting, reason: unknown country', __METHOD__);
            return false;
        }

        // otherwise there's a country available
        $objCheck = $this->Database->prepare('SELECT p.* FROM tl_countryresp c LEFT JOIN tl_page p ON p.id=c.pid WHERE c.iso_country_code=? AND p.published=1')
            ->execute(strtolower($country));

        // if no page is responsible for this country we stay on the fallback
        if (!$objCheck->numRows) {
            $this->debug(__CLASS__ . ' not redirecting, reason: no page for "' . $country . '"', __METHOD__);
            return false;
        }

        // We have a geo domain match for the user!

        // Detect if use is mobile and we have a mobile specific site
        if ($this->Environment->agent->mobile && $objCheck->gd_hasMobile) {
            $objMobile = \PageModel::findWithDetails($objCheck->gd_mobile);

            if (in_array('DomainLink', $this->Config->getActiveModules())) {
                $this->redirect($this->generateFrontendUrl($objMobile->row(), '', $objMobile->rootLanguage));
            }
            else {
                $this->redirect(($this->Environment->ssl ? 'https://' : 'http://') . $objMobile->domain . TL_PATH . '/' . $this->generateFrontendUrl($objMobile->row(), '', $objMobile->rootLanguage));
            }
        }

        // Redirect to the detected geo domain
        $this->redirect(($this->Environment->ssl ? 'https://' : 'http://') . $objCheck->dns . TL_PATH . '/');
    }


    /**
     * Find the matching country
     * @return string|false ISO counry code or false if not found
     */
    private function findCountry()
    {
        $ip = $this->Environment->ip;

        // transform ip to decimal representation
        $ip_dec = ip2long($ip);

        if ($ip_dec === false || $ip_dec < 1) {
            $this->log('IP detection did not work. Input was: "' . $ip . '"', 'GeoDomains detectRootPage()', TL_INFO);
            return false;
        }

        // lookup country in geodomains table
        $objCountry = $this->Database->prepare('SELECT iso_country_code FROM tl_geoipcountrywhois WHERE ? BETWEEN ip_from_dec AND ip_to_dec')
            ->execute($ip_dec);

        if (!$objCountry->numRows) {
            $this->log('No matching range in GeoIP table found. Input was: "' . $ip . '"', 'GeoDomains detectRootPage()', TL_INFO);
            return false;
        }

        // otherwise we found our country - great!
        return $objCountry->iso_country_code;
    }


    /**
     * Returns the root page detected by the default routine
     * @return Database_Result
     */
    private function getDefault()
    {
        $GLOBALS['TL_CONFIG']['doNotRedirectEmpty'] = true; // prevent redirecting here

        // unset the hook (infinite loop prevention)
        unset($GLOBALS['TL_HOOKS']['getRootPageFromUrl']['geodomains']);

        $objRootPage = $this->getRootPageFromUrl();

        $GLOBALS['TL_CONFIG']['doNotRedirectEmpty'] = false; // prevent redirecting here

        return $objRootPage;
    }
}

