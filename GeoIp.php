<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * PHP version 5
 * @copyright  Nothing Interactive 2012 <https://www.nothing.ch/>
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @author     Stefan Pfister <red@nothing.ch>
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

class GeoIp extends Frontend
{

    /**
     * Store the country for multiple requests
     * @var string
     */
    public static $strCountry;


	/**
	 * Replace Contao inserttags about the current domain
	 * @param string
	 * @return string|false
	 */
	public function replaceTags($strTag)
	{
		$arrTag = trimsplit('::', $strTag);

		if ($arrTag[0] == 'geo')
		{
			global $objPage;
			$objRootPage = $this->Database->execute("SELECT * FROM tl_page WHERE id=" . (int) $objPage->rootId);

			switch ($arrTag[1])
			{
				case 'region_name':
					return $objRootPage->geo_region_name ? $objRootPage->geo_region_name : ($objRootPage->pageTitle ? $objRootPage->pageTitle : $objRootPage->title);
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
		// detect the country using the client's IP address
		$country = $this->findCountry();

		// if no matching country is found we search for the GeoIp fallback
		if ($country === false)
		{
			$objRootPage = $this->Database->prepare("SELECT * FROM tl_page WHERE type='root' AND geo_fallback!=''")
			                              ->limit(1)
			                              ->execute();
		}
		else {
			// otherwise there's a country available
			$objRootPage = $this->Database->prepare("SELECT p.* FROM tl_countryresp c LEFT JOIN tl_page p ON p.id=c.pid WHERE p.type='root' AND c.iso_country_code=?")
			                              ->limit(1)
			                              ->execute(strtoupper($country));
		}

		// if no page is responsible for this country we stay on the fallback
		if (!$objRootPage->numRows)
		{
			return false;
		}

		$this->redirect($this->generateFrontendUrl($objRootPage->row()));
	}


	/**
	 * Hide navigation items that are geo-protected
	 * @param Template
	 * @see   https://github.com/contao/docs/blob/2.11/en/hooks/parseTemplate.md
	 */
	public function hideNavigationItems($objTemplate)
	{
    	if (strpos($objTemplate->getName(), 'nav_') === 0) {

        	$arrItems = $objTemplate->items;

        	foreach ($arrItems as $k => $arrItem) {

            	if ($arrItem['geo_protected']) {

                	$strCountry = $this->findCountry();

                	if ($strCountry === false || !in_array(strtolower($strCountry), $this->getCountriesForPage($arrItem['id'])))
                	{
                    	unset($arrItems[$k]);
                	}
            	}
        	}

        	$objTemplate->items = $arrItems;
    	}
	}


	/**
	 * Find the matching country
	 * @return string|false ISO country code or false if not found
	 */
	private function findCountry()
	{
	    if (self::$strCountry === null) {

    		$ip = $this->Environment->ip;

    		// transform ip to decimal representation
    		$ip_dec = ip2long($ip);

    		if ($ip_dec === false || $ip_dec < 1)
    		{
    			$this->log('IP detection did not work. Input was: "'. $ip .'"', 'GeoIp detectRootPage()', TL_INFO);
    			self::$strCountry = false;
    			return false;
    		}

    		// lookup country in geoip table
    		$objCountry = $this->Database->prepare('SELECT iso_country_code FROM tl_geoipcountrywhois WHERE ? BETWEEN ip_from_dec AND ip_to_dec')
    		                             ->limit(1)
    									 ->execute($ip_dec);

    		if (!$objCountry->numRows)
    		{
    			$this->log('No matching range in GeoIp table found. Input was: "'. $ip .'"', 'GeoIp detectRootPage()', TL_INFO);
    			self::$strCountry = false;
    			return false;
    		}

    		// otherwise we found our country - great!
    		self::$strCountry = strtolower($objCountry->iso_country_code);
        }

        return self::$strCountry;
	}


	/**
	 * Return a list of ISO country codes for the given page ID
	 * @param int
	 * @return array
	 */
	private function getCountriesForPage($intPage)
	{
    	$objCountries = $this->Database->prepare("SELECT c.iso_country_code FROM tl_countryresp c LEFT JOIN tl_page p ON p.id=c.pid WHERE p.id=?")
    	                               ->execute($intPage);

        return array_map('strtolower', $objCountries->fetchEach('iso_country_code'));
	}
}

