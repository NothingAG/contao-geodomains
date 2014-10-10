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
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    commercial
 */


class ModuleGDList extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_gd_list';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### GEODOMAINS NAVIGATION ###';
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

		$objRegions = $this->Database->prepare("SELECT * FROM tl_page WHERE type='root' AND fallback='1' AND gd_skip!='1' AND id!=? AND id!=(SELECT id FROM tl_page WHERE fallback='1' AND dns=?) ORDER BY sorting")->execute($objPage->rootId, $objPage->domain);

		$arrItems = array();

		while ($objRegions->next())
		{
			$arrItem = $objRegions->row();
			$arrItem['class'] = ($objRegions->gd_fallback) ? 'fallback' : '';
			$arrItem['href'] = ($this->Environment->ssl ? 'https://' : 'http://') . $objRegions->dns . TL_PATH . '/' . ($objRegions->gd_fallback ? $objRegions->language.'/' : '');
			$arrItem['title'] = specialchars(($objRegions->pageTitle) ? $objRegions->pageTitle : $objRegions->title, true);
			$arrItem['region'] = $objRegions->gd_region_name ? $objRegions->gd_region_name : ($objRegions->pageTitle ? $objRegions->pageTitle : $objRegions->title);
			$arrItem['city'] = $objRegions->gd_city_name ? $objRegions->gd_city_name : '';
            $arrItem['display'] = $objRegions->published;


            $objFile = \FilesModel::findByUuid($objRegions->gd_pic);

            $gdPic = $objFile->path;

            if ($objFile === null)
            {
                if (!\Validator::isUuid($this->singleSRC))
                {
                    return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
                }

                $gdPic = '';
            }

            if (!is_file(TL_ROOT . '/' . $objFile->path))
            {
                $gdPic = '';
            }

            // image
			if (is_file(TL_ROOT . '/' . $gdPic)) {
				$arrSize = @getimagesize(TL_ROOT . '/' . $gdPic);
				$arrItem['image_src'] = $gdPic;
				$arrItem['image_w'] = $arrSize[0];
				$arrItem['image_h'] = $arrSize[1];
			}

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

