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
 * Front end modules
 */
$GLOBALS['FE_MOD']['miscellaneous']['gd_list'] = 'ModuleGDList';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getRootPageFromUrl']['geodomains'] = array('GeoDomains', 'detectRootPage');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('GeoDomains', 'replaceTags');
