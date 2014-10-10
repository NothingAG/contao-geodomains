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
 * @license    [GNU Lesser General Public License (LGPL)](http://www.gnu.org/licenses/lgpl.html)
 */



/**
 * Add palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['gd_list']    = '{title_legend},name,headline,type;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';