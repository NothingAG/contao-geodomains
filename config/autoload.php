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
 * @copyright  Nothing Interactive 2014 <https://www.nothing.ch/>
 * @author     Lukas Walliser <xari@nothing.ch>
 * @license    commercial
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'GeoDomains'    => 'system/modules/geodomains/classes/GeoDomains.php',
	'ModuleGDList'  => 'system/modules/geodomains/modules/ModuleGDList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_gd_list'   => 'system/modules/geodomains/templates/modules',
	'nav_gd_list'   => 'system/modules/geodomains/templates/navigation',
));
