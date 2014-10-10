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
 * @copyright  nothing interactive 2014
 * @author     Lukas Walliser <xari@nothing.ch>
 * @license    [GNU Lesser General Public License (LGPL)](http://www.gnu.org/licenses/lgpl.html)
 */

/**
 * Table tl_countryresp
 */
$GLOBALS['TL_DCA']['tl_countryresp'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'iso_country_code' => 'index'
            )
        )
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'iso_country_code' => array
        (
            'sql'                     => "varchar(2) NOT NULL default ''"
        )
    )
);