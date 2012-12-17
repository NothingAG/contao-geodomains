# GeoIP

* Author:		Yanick Witschi [yanick.witschi@terminal42.ch](yanick.witschi@terminal42.ch)
* Contributors:	Andreas Schempp [andreas.schempp@terminal42.ch](andreas.schempp@terminal42.ch), Stefan Pfister [red@nothing.ch](red@nothing.ch)
* Website: 		[https://www.nothing.ch/](https://www.nothing.ch/)
* Version: 		1.0.0
* Date: 		2012-09-17
* License: 		[GNU Lesser Public License](http://opensource.org/licenses/lgpl-3.0.html)
* Dependencies:	Contao Version from 2.11

## Description
Redirects a visitor according to its IP-address to a specific root page. All settings are done in the page settings. The extension provides a frontend module to manually switch between the root-pages/regions.

## Installation and Usage
1. Install the GeoLite database from [MaxMind](http://dev.maxmind.com/geoip/geolite) in the same database as your Contao installation. The required DB scheme can be found in the file _config/database.sql_ (see table _tl_geoipcountrywhois_)
2. Copy the files into the _modules_ folder from Contao
3. Update the database (e.g. with the _Extension manager_)
4. Configure which root page is responsible for which country in the root page settings
5. Insert the frontend module _CountryList_ to manually switch between the regions
6. Enjoy :)

## Change Log

*1.0.0 (2012-07-12)*

* initial release