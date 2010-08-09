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
class MSNKeywords extends MSNAdCenter {

    const NAME = 'Keywords';

    // Keywords use a different namespace
    static protected $_xmlns = 'https://adcenter.microsoft.com/v6';

    static public $_objStruct = array(
        
            'CashBackInfo' => array(
                'CashBackAmount' => NULL,
                'CashBackStatus' => NULL,
                'CashBackText' => NULL,
            ),
        
            'BroadMatchBid' => array('Amount' => 0),
            'ContentMatchBid' => array('Amount' => 0),
            'ExactMatchBid' => array('Amount' => 0),
            'PhraseMatchBid' => array('Amount' => 0),
        
            'Id' => NULL,

            // Unlike other service requests, EditorialStatus breaks the service
            // when sent, even if it's null.  Quirk.
            //'EditorialStatus' => NULL,
            'NegativeKeywords' => array(),
            'OverridePriority' => NULL,
            'Param1' => NULL,
            'Param2' => NULL,
            'Param3' => NULL,
            'Status' => NULL,
            'Text' => NULL
    );

    /**
     *
     * @return array 'Keyword' helper structure
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
    static private function structExec($service, $adGroupId, array $keywords, $boolResponse = FALSE) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['Keywords'] = array('Keyword' => $keywords);
        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywords array of string keywords
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function add($adGroupId, array $keywords) {
        return self::structExec('AddKeywords', $adGroupId, $keywords);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywords array of string keywords
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function update($adGroupId, array $keywords) {
        return self::structExec('UpdateKeywords', $adGroupId, $keywords, TRUE);
    }

    /**
     *
     * @param <type> $service
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @param bool $boolResponse
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static private function statusExec($service, $adGroupId, array $keywordIds, $boolResponse = FALSE) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['KeywordIds'] = $keywordIds;
        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function delete($adGroupId, array $keywordIds) {
        return self::statusExec('DeleteKeywords', $adGroupId, $keywordIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function pause($adGroupId, array $keywordIds) {
        return self::statusExec('PauseKeywords', $adGroupId, $keywordIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function resume($adGroupId, array $keywordIds) {
        return self::statusExec('ResumeKeywords', $adGroupId, $keywordIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getByIds($adGroupId, array $keywordIds) {
        return self::statusExec('GetKeywordsByIds', $adGroupId, $keywordIds);
    }

    // -------------------------------------------------------------------------

    // -- ADGROUPS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getByAdGroupId($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execRespond('GetKeywordsByAdGroupId', $params);
    }

    /**
     *
     * @param int $campaignId Campaign IDs
     * @param array $adGroupIds array of int Ad Group IDs
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getNegativeByAdGroupId($campaignId, array $adGroupIds) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        return self::execRespond('GetNegativeKeywordsByAdGroupIds', $params);
    }

    /**
     *
     * @param int $campaignId Campaign IDs
     * @param array $agNegativeKeywords
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function setNegativeToAdGroups($campaignId, array $agNegativeKeywords) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $kwArr = array();
        foreach ($agNegativeKeywords as $adGroupId => $keywords) {
            $kwArr[] = array('AdGroupId' => $adGroupId, 'NegativeKeywords' => $keywords);
        }
        $params['AdGroupNegativeKeywords'] = $kwArr;

        return self::execute('SetNegativeKeywordsToAdGroups', $params);
    }

    // -- CAMPAIGNS

    /**
     *
     * @param int $accountId Account ID
     * @param array $cNegativeKeywords Associative Array of CampaignID => array(negative keywords)s
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function setNegativeToCampaigns($accountId, array $cNegativeKeywords) {
        $params = array();
        $params['AccountId'] = $accountId;
        $cArr = array();
        foreach ($cNegativeKeywords as $campaignId => $keywords) {
            $kwArr[] = array('CampaignId' => $campaignId, 'NegativeKeywords' => $keywords);
        }
        $params['CampaignNegativeKeywords'] = $kwArr;

        return self::execute('SetNegativeKeywordsToCampaigns', $params);
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaignIds array of int Campaign IDs
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getNegativeByCampaignIds($accountId, array $campaignIds) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['CampaignIds'] = $campaignIds;
        return self::execRespond('GetNegativeKeywordsByCampaignIds', $params);
    }
}
?>