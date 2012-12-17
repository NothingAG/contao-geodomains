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
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @author     Stefan Pfister <red@nothing.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_page']['config']['ctable'][] = 'tl_countryresp';

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'geo_protected';
$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace('{sitemap_legend:hide}', '{geoip_legend},geo_region_name,geo_country_resp,geo_fallback;{sitemap_legend:hide}', $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace('protected', 'protected,geo_protected', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['redirect'] = str_replace('protected', 'protected,geo_protected', $GLOBALS['TL_DCA']['tl_page']['palettes']['redirect']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['forward'] = str_replace('protected', 'protected,geo_protected', $GLOBALS['TL_DCA']['tl_page']['palettes']['forward']);
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['geo_protected'] = 'geo_country_resp';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['fallback']['eval']['submitOnChange'] = true;

$GLOBALS['TL_DCA']['tl_page']['fields']['geo_region_name'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_page']['geo_region_name'],
	'exclude'		=> true,
	'inputType'		=> 'text',
	'eval'			=> array('tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_page']['fields']['geo_country_resp'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_page']['geo_country_resp'],
	'exclude'		=> true,
	'inputType'		=> 'select',
	'options'		=> $this->getCountries(),
	'eval'			=> array('multiple'=>true, 'chosen'=>true),
	'load_callback'	=> array
	(
		array('tl_page_geoip', 'loadCountries')
	),
	'save_callback'	=> array
	(
		array('tl_page_geoip', 'saveCountries')
	)
);

$GLOBALS['TL_DCA']['tl_page']['fields']['geo_fallback'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_page']['geo_fallback'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'eval'			=> array('tl_class'=>'w50 m12', 'fallback'=>true)
);

$GLOBALS['TL_DCA']['tl_page']['fields']['geo_protected'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_page']['geo_protected'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'eval'			=> array('submitOnChange'=>true, 'tl_class'=>'clr'),
);



class tl_page_geoip extends Backend
{
	/**
	 * Load countries from association table
	 * @param mixed
	 * @param DataContainer
	 */
	public function loadCountries($varValue, DataContainer $dc)
	{
		$arrData = array();
		$objData = $this->Database->prepare('SELECT iso_country_code FROM tl_countryresp WHERE pid=?')->execute($dc->id);

		if ($objData->numRows)
		{
			$arrData = $objData->fetchEach('iso_country_code');
		}

		return serialize($arrData);
	}


	/**
	 * Save countries to association table
	 * @param mixed
	 * @param DataContainer
	 */
	public function saveCountries($varValue, DataContainer $dc)
	{
		$arrData = deserialize($varValue, true);

		$this->Database->prepare('DELETE FROM tl_countryresp WHERE pid=?')->execute($dc->id);

		foreach ($arrData as $countryCode)
		{
			$arrSet = array();
			$arrSet['iso_country_code']	= $countryCode;
			$arrSet['pid']				= $dc->id;
			$this->Database->prepare('INSERT INTO tl_countryresp %s')->set($arrSet)->execute();
		}

		return $varValue;
	}
}
