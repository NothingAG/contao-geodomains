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


class ModuleGeoList extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_geo_list';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### GEOIP NAVIGATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$objRegions = $this->Database->prepare("SELECT * FROM tl_page WHERE type='root' AND geo_region_name!='' AND id!=? ORDER BY sorting")
											 ->execute($objPage->rootId);
		
		$arrItems = array();

		while ($objRegions->next())
		{
			$arrItem = $objRegions->row();
			$arrItem['class'] = ($objRegions->geo_fallback) ? 'fallback' : '';
			$arrItem['href'] = $this->generateFrontendUrl($objRegions->row());
			$arrItem['title'] = specialchars(($objRegions->pageTitle) ? $objRegions->pageTitle : $objRegions->title, true);
			$arrItem['region'] = $objRegions->geo_region_name ? $objRegions->geo_region_name : ($objRegions->pageTitle ? $objRegions->pageTitle : $objRegions->title);

			$arrItems[] = $arrItem;
		}

		// Add classes first and last
		if (!empty($arrItems))
		{
			$last = $objRegions->numRows - 1;

			$arrItems[0]['class'] = trim($arrItems[0]['class'] . ' first');
			$arrItems[$last]['class'] = trim($arrItems[$last]['class'] . ' last');
		}

		$this->Template->items = $arrItems;
	}
}

