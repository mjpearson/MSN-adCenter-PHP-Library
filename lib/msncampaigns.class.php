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
class MSNCampaigns extends MSNAdCenter {

    const NAME = 'Campaigns';

    public $_objStruct = array('BroadMatchBid' => NULL,
            'CashBackInfo' => NULL,
            'ConversionTrackingEnabled' => NULL,
            'ConversionTrackingScript' => NULL,
            'DailyBudget' => NULL,
            'DaylightSaving' => NULL,
            'Description' => NULL,
            'Id' => NULL,
            'MonthlyBudget' => NULL,
            'Name' => NULL,
            'NegativeCampaigns' => NULL,
            'NegativeSiteUrls' => NULL,
            'Status' => NULL,
            'TimeZone' => NULL
    );

    /**
     *
     * @return array 'Campaign' helper structure
     */
    public function getObjStruct() {
        return $this->_objStruct;
    }

    /**
     *
     * @link http://msdn.microsoft.com/en-us/library/cc817316.aspx
     * @param string $service MSN AdCenter Service Operation
     * @param int $accountId Account ID
     * @param array $campaigns Array of 'Campaign' structures
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    private function structExec($service, $accountId, array $campaigns, $boolResponse = FALSE) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['Campaigns'] = array('Keyword' => $campaigns);
        if ($boolResponse) {
            return $this->execute($service, $params);
        } else {
            return $this->execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaigns Array of 'Campaign' structures
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function add($accountId, array $campaigns) {
        return $this->structExec('AddCampaigns', $accountId, $campaigns);
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaigns Array of 'Campaign' structures
     * @return bool operation completed (TrackingID is in response header)
     */
    public function update($accountId, array $campaigns) {
        return $this->structExec('UpdateCampaigns', $accountId, $campaigns, TRUE);
    }

    /**
     *
     * @param string $service MSN AdCenter Service Operation
     * @param int $accountId Account ID
     * @param array $campaignIds array of int Campaign IDs
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    private function statusExec($service, $accountId, array $campaignIds, $boolResponse = FALSE) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['CampaignIds'] = $campaignIds;
        if ($boolResponse) {
            return $this->execute($service, $params);
        } else {
            return $this->execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaignIds array of int Campaign IDs
     * @return bool operation completed (TrackingID is in response header)
     */
    public function delete($accountId, array $campaignIds) {
        return $this->statusExec('DeleteCampaigns', $accountId, $campaignIds, TRUE);
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaignIds array of int Campaign IDs
     * @return bool operation completed (TrackingID is in response header)
     */
    public function pause($accountId, array $campaignIds) {
        return $this->statusExec('PauseCampaigns', $accountId, $campaignIds, TRUE);
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaignIds array of int Campaign IDs
     * @return bool operation completed (TrackingID is in response header)
     */
    public function resume($accountId, array $campaignIds) {
        return $this->statusExec('ResumeCampaigns', $accountId, $campaignIds, TRUE);
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaignIds array of int Campaign IDs
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getByIds($accountId, array $campaignIds) {
        return $this->statusExec('GetCampaignsByIds', $accountId, $campaignIds);
    }

    // -------------------------------------------------------------------------

    /**
     *
     * @param int $accountId Account ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getByAccountId($accountId) {
        $params = array();
        $params['AccountId'] = $accountId;
        return $this->execRespond('GetCampaignsByAccountID', $params);
    }

    /**
     *
     * @param int $accountId Account ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getInfoByAccountId($accountId) {
        $params = array();
        $params['AccountId'] = $accountId;
        return $this->execRespond('GetCampaignsInfoByAccountID', $params);
    }

    // -- KEYWORDS

    /**
     *
     * @param int $accountId Account ID
     * @param array $cNegativeKeywords Associative Array of CampaignID => array(negative keywords)s
     * @return bool operation completed (TrackingID is in response header)
     */
    public function setNegativeKeywords($accountId, array $cNegativeKeywords) {
        $params = array();
        $params['AccountId'] = $accountId;
        $cArr = array();
        foreach ($cNegativeKeywords as $campaignId => $keywords) {
            $kwArr[] = array('CampaignId' => $campaignId, 'NegativeKeywords' => $keywords);
        }
        $params['CampaignNegativeKeywords'] = $kwArr;

        return $this->execute('SetNegativeKeywordsToCampaigns', $params);
    }

    /**
     *
     * @param int $accountId Account ID
     * @param array $campaignIds array of int Campaign IDs
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getNegativeKeywords($accountId, array $campaignIds) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['CampaignIds'] = $campaignIds;
        return $this->execRespond('GetNegativeKeywordsByCampaignIds', $params);
    }

    // -- TARGETS

    /**
     *
     * @param array $campaignIds array of int Campaign IDs
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getTargets(array $campaignIds) {
        $params = array();
        $params['CampaignIds'] = $campaignIds;
        return $this->execRespond('GetTargetsByCampaignIds', $params);
    }

    // -- ADGROUPS

    /**
     *
     * @param int $campaignId Campaign ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getAdGroups($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return $this->execRespond('GetAdGroupsByCampaignId', $params);
    }

    /**
     *
     * @param int $campaignId Campaign ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getAdGroupsInfo($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return $this->execRespond('GetAdGroupsInfoByCampaignId', $params);
    }
}
?>