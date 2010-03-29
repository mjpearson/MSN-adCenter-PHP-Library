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
class MSNAdGroups extends MSNAdCenter {

    const NAME = 'AdGroups';

    static private $_objStruct = array('AdDistribution' => NULL,
            'BiddingModel' => NULL,
            'BroadMatchBid' => NULL,
            'CashBackInfo' => NULL,
            'ContentMatchBid' => NULL,
            'EndDate' => NULL,
            'ExactMatchBid' => NULL,
            'Id' => NULL,
            'LanguageAndRegion' => NULL,
            'Name' => NULL,
            'NegativeKeywords' => NULL,
            'NegativeSiteUrls' => NULL,
            'PhraseMatchBid' => NULL,
            'PricingModel' => NULL,
            'StartDate' => NULL,
            'Status'  => NULL
    );

    /**
     *
     * @return array 'AdGroup' helper structure
     */
    static public function getObjStruct() {
        return self::$_objStruct;
    }

    /**
     *
     * @link http://msdn.microsoft.com/en-us/library/cc817316.aspx
     * @param string $service MSN AdCenter Service Operation
     * @param int $campaignId campaign id
     * @param array $adGroups Array of 'AdGroup' structures
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    static private function structExec($service, $campaignId, array $adGroups, $boolResponse = FALSE) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroups'] = array('AdGroup' => $adGroups);
        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroups Array of 'AdGroup' structures
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function add($campaignId, array $adGroups) {
        return self::structExec('AddAdGroups', $campaignId, $adGroups);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroups Array of 'AdGroup' structures
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function update($campaignId, array $adGroups) {
        return self::structExec('UpdateAdGroups', $campaignId, $adGroups, TRUE);
    }

    /**
     *
     * @param string $service MSN AdCenter Service Operation
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    static private function statusExec($service, $campaignId, array $adGroupIds, $boolResponse = FALSE) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function delete($campaignId, array $adGroupIds) {
        return self::statusExec('DeleteAdGroups', $campaignId, $adGroupIds, TRUE);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function pause($campaignId, array $adGroupIds) {
        return self::statusExec('PauseAdGroups', $campaignId, $adGroupIds, TRUE);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function resume($campaignId, array $adGroupIds) {
        return self::statusExec('ResumeAdGroups', $campaignId, $adGroupIds, TRUE);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getByIds($campaignId, array $adGroupIds) {
        return self::statusExec('GetAdGroupsByIds', $campaignId, $adGroupIds);
    }

    // -------------------------------------------------------------------------

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function submitForApproval($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execute('SubmitAdGroupForApproval', $params);
    }

    // -- CAMPAIGN

    /**
     *
     * @param int $campaignId campaign id
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getByCampaignId($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return self::execRespond('GetAdGroupsByCampaignId', $params);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getInfoByCampaignId($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return self::execRespond('GetAdGroupsInfoByCampaignId', $params);
    }

    // -- ADS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getAds($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execRespond('GetAdsByAdGroupId', $params);
    }

    // -- SITEPLACEMENTS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getSitePlacements($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execRespond('GetSitePlacementsByAdGroupId', $params);
    }

    // -- TARGETS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param int $targetId Target ID
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function setTargetTo($adGroupId, $targetId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['TargetId'] = $targetId;
        return self::execute('SetTargetToAdGroup', $params);
    }

    /**
     *
     * @param array $adGroupIds array of integer AdGroup id's
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getTargets(array $adGroupIds) {
        $params = array();
        $params['AdGroupIds'] = $adGroupIds;
        return self::execRespond('GetTargetsByAdGroupIds', $params);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function deleteTarget($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execute('DeleteTargetFromAdGroup', $params);
    }

    // -- KEYWORDS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getKeywords($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execRespond('GetKeywordsByAdGroupId', $params);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getNegativeKeywords($campaignId, array $adGroupIds) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        return self::execRespond('GetNegativeKeywordsByAdGroupIds', $params);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $agNegativeKeywords
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function setNegativeKeywords($campaignId, array $agNegativeKeywords) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $kwArr = array();
        foreach ($agNegativeKeywords as $adGroupId => $keywords) {
            $kwArr[] = array('AdGroupId' => $adGroupId, 'NegativeKeywords' => $keywords);
        }
        $params['AdGroupNegativeKeywords'] = $kwArr;

        return self::execute('SetNegativeKeywordsToAdGroups', $params);
    }

    // -- BEHAVIOURAL BIDS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function getBehavioralBids($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return self::execRespond('GetBehavioralBidsByAdGroupId', $params);
    }
}
?>