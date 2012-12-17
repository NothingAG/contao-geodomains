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
 * Front end modules
 */
$GLOBALS['FE_MOD']['miscellaneous']['geo_list'] = 'ModuleGeoList';


/**
 * Hooks
 */
if (TL_MODE == 'FE') {
    $GLOBALS['TL_HOOKS']['getRootPageFromUrl']['geoip'] = array('GeoIp', 'detectRootPage');
    $GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('GeoIp', 'replaceTags');
    $GLOBALS['TL_HOOKS']['parseTemplate'][] = array('GeoIp', 'hideNavigationItems');
}
