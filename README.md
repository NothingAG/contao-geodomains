# CONTAO EXTENSION: GEOIP
Redirects a visitor according to its IP address to a specific root page. All settings are done in the page settings. The extension provides a frontend module to manually switch between the root-pages/ regions.

## SETUP AND USAGE
### Prerequisites
 * Contao 2.11+

### Installation
1. Install the GeoLite database from [MaxMind](http://www.maxmind.com/app/geoip_country) in the same database as your Contao installation. The required DB scheme can be found in the file _config/database.sql_ (see table _tl_geoipcountrywhois_)
2. Copy the files into the _modules_ folder from Contao
3. Update the database (e.g. with the _Extension manager_)
4. Configure which root page is responsible for which country in the root page settings
5. Insert the frontend module _CountryList_ to manually switch between the regions
6. Enjoy!

## VERSION HISTORY
### 1.0.0 (2012-07-12)*
#### Initial release

## LICENSE
* Author:		Nothing Interactive, Switzerland
* Website: 		[https://www.nothing.ch/](https://www.nothing.ch/)
* Version: 		1.0.0
* Date: 		2012-09-17
* License: 		[GNU Lesser General Public License (LGPL)](http://www.gnu.org/licenses/lgpl.html)