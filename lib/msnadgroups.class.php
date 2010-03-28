<?php
/**
 * An easy to use MSN adCenter PHP Library
 *
 * @name      MSN adCenter PHP Library
 * @author    Michael Taggart <mtaggart@envoymediagroup.com>
 * @author    Michael Pearson <michael@cloudspark.com.au>
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

    private $_objStruct = array('AdDistribution' => NULL,
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
    public function getObjStruct() {
        return $this->_objStruct;
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
    private function structExec($service, $campaignId, array $adGroups, $boolResponse = FALSE) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroups'] = array('AdGroup' => $adGroups);
        if ($boolResponse) {
            return $this->execute($service, $params);
        } else {
            return $this->execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroups Array of 'AdGroup' structures
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function add($campaignId, array $adGroups) {
        return $this->structExec('AddAdGroups', $campaignId, $adGroups);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroups Array of 'AdGroup' structures
     * @return bool operation completed (TrackingID is in response header)
     */
    public function update($campaignId, array $adGroups) {
        return $this->structExec('UpdateAdGroups', $campaignId, $adGroups, TRUE);
    }

    /**
     *
     * @param string $service MSN AdCenter Service Operation
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    private function statusExec($service, $campaignId, array $adGroupIds, $boolResponse = FALSE) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        if ($boolResponse) {
            return $this->execute($service, $params);
        } else {
            return $this->execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return bool operation completed (TrackingID is in response header)
     */
    public function delete($campaignId, array $adGroupIds) {
        return $this->statusExec('DeleteAdGroups', $campaignId, $adGroupIds, TRUE);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return bool operation completed (TrackingID is in response header)
     */
    public function pause($campaignId, array $adGroupIds) {
        return $this->statusExec('PauseAdGroups', $campaignId, $adGroupIds, TRUE);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return bool operation completed (TrackingID is in response header)
     */
    public function resume($campaignId, array $adGroupIds) {
        return $this->statusExec('ResumeAdGroups', $campaignId, $adGroupIds, TRUE);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getByIds($campaignId, array $adGroupIds) {
        return $this->statusExec('GetAdGroupsByIds', $campaignId, $adGroupIds);
    }

    // -------------------------------------------------------------------------

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return bool operation completed (TrackingID is in response header)
     */
    public function submitForApproval($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('SubmitAdGroupForApproval', $params);
    }

    // -- CAMPAIGN

    /**
     *
     * @param int $campaignId campaign id
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getByCampaignId($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return $this->execRespond('GetAdGroupsByCampaignId', $params);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getInfoByCampaignId($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return $this->execRespond('GetAdGroupsInfoByCampaignId', $params);
    }

    // -- ADS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getAds($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execRespond('GetAdsByAdGroupId', $params);
    }

    // -- SITEPLACEMENTS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getSitePlacements($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execRespond('GetSitePlacementsByAdGroupId', $params);
    }

    // -- TARGETS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param int $targetId Target ID
     * @return bool operation completed (TrackingID is in response header)
     */
    public function setTargetTo($adGroupId, $targetId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['TargetId'] = $targetId;
        return $this->execute('SetTargetToAdGroup', $params);
    }

    /**
     *
     * @param array $adGroupIds array of integer AdGroup id's
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getTargets(array $adGroupIds) {
        $params = array();
        $params['AdGroupIds'] = $adGroupIds;
        return $this->execRespond('GetTargetsByAdGroupIds', $params);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return bool operation completed (TrackingID is in response header)
     */
    public function deleteTarget($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('DeleteTargetFromAdGroup', $params);
    }

    // -- KEYWORDS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getKeywords($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execRespond('GetKeywordsByAdGroupId', $params);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $adGroupIds array of integer AdGroup id's
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getNegativeKeywords($campaignId, array $adGroupIds) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        return $this->execRespond('GetNegativeKeywordsByAdGroupIds', $params);
    }

    /**
     *
     * @param int $campaignId campaign id
     * @param array $agNegativeKeywords
     * @return bool operation completed (TrackingID is in response header)
     */
    public function setNegativeKeywords($campaignId, array $agNegativeKeywords) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $kwArr = array();
        foreach ($agNegativeKeywords as $adGroupId => $keywords) {
            $kwArr[] = array('AdGroupId' => $adGroupId, 'NegativeKeywords' => $keywords);
        }
        $params['AdGroupNegativeKeywords'] = $kwArr;

        return $this->execute('SetNegativeKeywordsToAdGroups', $params);
    }

    // -- BEHAVIOURAL BIDS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getBehavioralBids($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execRespond('GetBehavioralBidsByAdGroupId', $params);
    }
}
?>