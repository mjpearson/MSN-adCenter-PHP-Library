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
 * @version   $Rev$
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
 */
class MSNAds extends MSNAdCenter {
    const NAME = 'Ads';

    // Ad Structure helper
    static private $_objStruct = array(
            'Title' => NULL,
            'DestinationUrl' => NULL,
            'DisplayUrl' => NULL,
            'Text' => NULL
    );

    /**
     *
     * @return array 'Ad' helper structure
     */
    static public function getObjStruct() {
        return self::$_objStruct;
    }

    /**
     *
     * @link http://msdn.microsoft.com/en-us/library/cc817316.aspx
     * @param string $service MSN AdCenter Service Operation
     * @param int $adGroupId Ad Group ID
     * @param array $ads Array of 'Ad' structures
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    static private function structExec($service, $adGroupId, array $ads, $boolResponse = FALSE) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $adSVar = array();
        foreach ($ads as $adStruct) {

            $adSVar[] = new SoapVar($adStruct, SOAP_ENC_OBJECT, 'TextAd', self::$_xmlns);
        }
        $params['Ads'] = array('Ad' => $adSVar);
        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $ads Array of 'Ad' structures
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function add($adGroupId, array $ads) {
        return self::structExec('AddAds', $adGroupId, $ads);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $ads Array of 'Ad' structures
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function update($adGroupId, array $ads) {
        return self::structExec('UpdateAds', $adGroupId, $ads, TRUE);
    }

    /**
     *
     * @param string $service MSDN AdCenter Service Operation
     * @param int $adGroupId Ad Group ID
     * @param array $adIds Ad IDs
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    static private function statusExec($service, $adGroupId, array $adIds, $boolResponse = FALSE) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['AdIds'] = $adIds;
        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $adIds Ad IDs
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function delete($adGroupId, array $adIds) {
        return self::statusExec('DeleteAds', $adGroupId, $adIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $adIds Ad IDs
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function pause($adGroupId, array $adIds) {
        return self::statusExec('PauseAds', $adGroupId, $adIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $adIds Ad IDs
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function resume($adGroupId, array $adIds) {
        return self::statusExec('ResumeAds', $adGroupId, $adIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $adIds Ad IDs
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getByIds($adGroupId, array $adIds) {
        return self::statusExec('GetAdsByIds', $adGroupId, $adIds);
    }

    // -------------------------------------------------------------------------

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param <type> $editorialStatus
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getByEditorialStatus($adGroupId, $editorialStatus) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['EditorialStatus'] = $editorialStatus;
        return self::execRespond('GetAdsByEditorialStatus', $params);
    }

    // -- ADGROUPS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getByAdGroupId($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execRespond('GetAdsByAdGroupId', $params);
    }
}
?>