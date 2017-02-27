# [UNMAINTAINED]
This project is not maintained anymore.

# CONTAO EXTENSION: GEODOMAINS
(Previously GEOIP)

Redirects a visitor according to its IP address to a specific root page. All settings are done in the page settings. The extension provides a frontend module to manually switch between the root-pages/ regions.

## SETUP AND USAGE
### Prerequisites
 * Contao 3.2.x
 
### Installation
1. Install the GeoLite database from [MaxMind](http://www.maxmind.com/app/geoip_country) in the same database as your Contao installation. The required DB scheme can be found in the file _config/database.sql_ (see table _tl_geoipcountrywhois_)
2. Copy the files into the _modules_ folder from Contao
3. Update the database (e.g. with the _Extension manager_)
4. Configure which root page is responsible for which country in the root page settings
5. Insert the frontend module _CountryList_ to manually switch between the regions
6. Enjoy!

### GeoIp & Proxy

Note that if x forwarded for is set the redirect might fail to work. If this is the case (mobile on 3g). To get around
that make sure an initconfig.php file is added to system/config and it contains the following lines:

    unset($_SERVER['HTTP_X_FORWARDED_FOR']);
    unset($_SERVER['HTTP_X_FORWARDED_HOST']);

## VERSION HISTORY

### 2.2.0 (2014-09-24)

* Switch to uuid for images
* Added skip functionality for specific visible page tress to not appear in the gd list (available in site structure)

### 2.1.0 (2013-08-13)

* Added skip functionality for hidden page tree entries (set visibility in the backend)

### 2.0.0 (2013-06-05)

* Initial Contao 3 version
 
### 1.0.0 (2012-07-12)

* Initial release

## KNOWN ISSUES

 * We had to add the "gd_fallback" flag manually to certain page roots, otherwise it wouldn't use the geo redirection if
the browser had something else than English as preferred language.
 * Unable to fully transfer database.sql to dca files

## LICENSE
* Author:		Nothing Interactive, Switzerland
* Website: 		[https://www.nothing.ch/](https://www.nothing.ch/)
* Version: 		2.2.0
* Date: 		2014-10-10
* License: 		[GNU Lesser General Public License (LGPL)](http://www.gnu.org/licenses/lgpl.html)
