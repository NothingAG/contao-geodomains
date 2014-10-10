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
 * @license    [GNU Lesser General Public License (LGPL)](http://www.gnu.org/licenses/lgpl.html)
 */


/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_page']['config']['ctable'][] = 'tl_countryresp';
$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = array('tl_page_geodomains', 'onLoad');


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['fallback']['eval']['submitOnChange'] = TRUE;

$GLOBALS['TL_DCA']['tl_page']['fields']['gd_skip'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_skip'],
    'exclude'		=> TRUE,
    'inputType'		=> 'checkbox',
    'eval'			=> array('tl_class'=>'clr'),
    'sql'           => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['gd_region_name'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_region_name'],
    'exclude'		=> TRUE,
    'inputType'		=> 'text',
    'eval'			=> array('tl_class'=>'w50'),
    'sql'           => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['gd_city_name'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_city_name'],
    'exclude'		=> TRUE,
    'inputType'		=> 'text',
    'eval'			=> array('tl_class'=>'w50'),
    'sql'           => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['gd_pic'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_pic'],
    'exclude'		=> TRUE,
    'inputType'		=> 'fileTree',
    'eval'			=> array('fieldType'=>'radio', 'files'=>TRUE, 'filesOnly'=>TRUE, 'extensions'=>'jpeg,jpg,png,gif', 'tl_class'=>'clr'),
    'sql'           => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['gd_country_resp'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_country_resp'],
    'exclude'		=> TRUE,
    'inputType'		=> 'select',
    'options'		=> $this->getCountries(),
    'eval'			=> array('multiple'=>TRUE, 'chosen'=>TRUE, 'tl_class'=>'w50'),
    'sql'           => 'blob NULL',
    'load_callback'	=> array
    (
        array('tl_page_geodomains', 'loadCountries')
    ),
    'save_callback'	=> array
    (
        array('tl_page_geodomains', 'saveCountries')
    )
);

$GLOBALS['TL_DCA']['tl_page']['fields']['gd_fallback'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_fallback'],
    'exclude'		=> TRUE,
    'inputType'		=> 'checkbox',
    'eval'			=> array('tl_class'=>'w50 m12', 'fallback'=>TRUE),
    'sql'           => "char(1) NOT NULL default ''"
);

// TODO DEPRECATED
$GLOBALS['TL_DCA']['tl_page']['fields']['gd_hasMobile'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_hasMobile'],
    'exclude'		=> TRUE,
    'inputType'		=> 'checkbox',
    'eval'			=> array('tl_class'=>'clr', 'submitOnChange'=>TRUE),
    'sql'           => "char(1) NOT NULL default ''"
);

// TODO DEPRECATED
$GLOBALS['TL_DCA']['tl_page']['fields']['gd_mobile'] = array
(
    'label'			=> &$GLOBALS['TL_LANG']['tl_page']['gd_mobile'],
    'exclude'		=> TRUE,
    'inputType'		=> 'pageTree',
    'eval'			=> array('fieldType'=>'radio', 'tl_class'=>'clr'),
    'sql'           => "int(10) unsigned NOT NULL default '0'"
);


/**
 * Class tl_page_geodomains
 */
class tl_page_geodomains extends Backend
{

    /**
     * onPage load check for fallback.
     * @param DataContainer $dc Data container
     */
    public function onLoad(DataContainer $dc)
    {
        $objEntry = $this->Database->prepare('SELECT fallback FROM tl_page WHERE id=?')->execute($dc->id);

        if ($objEntry->fallback)
        {
            $GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace('{sitemap_legend:hide}', '{geodomains_legend},gd_skip,gd_region_name,gd_city_name,gd_pic,gd_country_resp,gd_fallback,gd_hasMobile;{sitemap_legend:hide}', $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
            $GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'gd_hasMobile';
            $GLOBALS['TL_DCA']['tl_page']['subpalettes']['gd_hasMobile'] = 'gd_mobile';
        }
    }


    /**
     * Load countries from association table
     * @param mixed
     * @param DataContainer
     * @return string serialized data
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
        $arrData = deserialize($varValue, TRUE);

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
