<?php
/**
 * An easy to use MSN adCenter PHP Library
 *
 * @name      MSN adCenter PHP Library
 * @author    Michael Taggart <mtaggart@envoymediagroup.com>
 * @author    Michael Pearson <michael@phpgrease.net>
 * @copyright (c) 2010 Envoy Media Group
 * @link      http://www.envoymediagroup.com
 * @license   MIT
 * @version   $Rev: 5 $
 * @internal  $Id$
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * Shameless Plug:
 * Envoy Media Group is a marketing company that specializes in PPC, Email, TV,
 * Radio, etc. If you are a solid PHP coder and want to be a part of a fun, vibrant
 * team that tackle complex problems please send your info to mtaggart@envoymediagroup.com
 * We are always looking for talented developers and welcome the opportunity to discuss
 * the possibilities of you joining our team.
 *
 */
error_reporting(E_ALL);
$_liveEnv = FALSE;

if ($_liveEnv) {
    // LIVE CREDENTIALS
    define('API_USER', '');
    define('API_PASSWORD', '');
    define('API_KEY', '');
    define('API_KEY_DEV', '');
    define('API_CUSTOMER_ID', '');
    define('MSDNAPI_SERVICE_URL', 'https://adcenterapi.microsoft.com/api/advertiser/v6/CampaignManagement/CampaignManagementService.svc');
} else {
    // SANDBOX CREDENTIALS
    define('API_USER', '');
    define('API_PASSWORD', '');
    define('API_KEY', '');	// This should always be empty
    define('API_KEY_DEV', '');
    define('API_CUSTOMER_ID', '');
    define('MSDNAPI_SERVICE_URL', 'https://sandboxapi.adcenter.microsoft.com/Api/Advertiser/v6/CampaignManagement/CampaignManagementService.svc');
    //soapui mock
    //define('MSDNAPI_SERVICE_URL', 'http://localhost:8088/mockBasicHttpBinding_ICampaignManagementService');
}

ini_set("soap.wsdl_cache_enabled", "0");

// Include the base abstract and autoloader
require_once(dirname(__FILE__).'/lib/msnadcenter.class.php');
?>