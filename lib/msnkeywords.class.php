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

    public $_objStruct = array('BroadMatchBid' => NULL,
            'CashBackInfo' => NULL,
            'ContentMatchBid' => NULL,
            'EditorialStatus' => NULL,
            'ExactMatchBid' => NULL,
            'Id' => NULL,
            'NegativeKeywords' => NULL,
            'OverridePriority' => NULL,
            'Param1' => NULL,
            'Param2' => NULL,
            'Param3' => NULL,
            'PhraseMatchBid' => NULL,
            'Status' => NULL,
            'Text' => NULL
    );

    /**
     *
     * @return array 'Keyword' helper structure
     */
    public function getObjStruct() {
        return $this->_objStruct;
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
    private function structExec($service, $adGroupId, array $keywords, $boolResponse = FALSE) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['Keywords'] = array('Keyword' => $keywords);
        if ($boolResponse) {
            return $this->execute($service, $params);
        } else {
            return $this->execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywords array of string keywords
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function add($adGroupId, array $keywords) {
        return $this->structExec('AddKeywords', $adGroupId, $keywords);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywords array of string keywords
     * @return bool operation completed (TrackingID is in response header)
     */
    public function update($adGroupId, array $keywords) {
        return $this->structExec('UpdateKeywords', $adGroupId, $keywords, TRUE);
    }

    /**
     *
     * @param <type> $service
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @param <type> $boolResponse
     * @return mixed default response type (Object, Array or Raw XML)
     */
    private function statusExec($service, $adGroupId, array $keywordIds, $boolResponse = FALSE) {
        $params = array();
        $params['CampaignId'] = $adGroupId;
        $params['KeywordIds'] = $keywordIds;
        if ($boolResponse) {
            return $this->execute($service, $params);
        } else {
            return $this->execRespond($service, $params);
        }
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return bool operation completed (TrackingID is in response header)
     */
    public function delete($adGroupId, array $keywordIds) {
        return $this->statusExec('DeleteKeywords', $adGroupId, $keywordIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return bool operation completed (TrackingID is in response header)
     */
    public function pause($adGroupId, array $keywordIds) {
        return $this->statusExec('PauseKeywords', $adGroupId, $keywordIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return bool operation completed (TrackingID is in response header)
     */
    public function resume($adGroupId, array $keywordIds) {
        return $this->statusExec('ResumeKeywords', $adGroupId, $keywordIds, TRUE);
    }

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @param array $keywordIds
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getByIds($adGroupId, array $keywordIds) {
        return $this->statusExec('GetKeywordsByIds', $adGroupId, $keywordIds);
    }

    // -------------------------------------------------------------------------

    // -- ADGROUPS

    /**
     *
     * @param int $adGroupId Ad Group ID
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getByAdGroupId($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execRespond('GetKeywordsByAdGroupId', $params);
    }

    /**
     *
     * @param int $campaignId Campaign IDs
     * @param array $adGroupIds array of int Ad Group IDs
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function getNegativeByAdGroupId($campaignId, array $adGroupIds) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        return $this->execRespond('GetNegativeKeywordsByAdGroupIds', $params);
    }

    /**
     *
     * @param int $campaignId Campaign IDs
     * @param array $agNegativeKeywords
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function setNegativeToAdGroups($campaignId, array $agNegativeKeywords) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $kwArr = array();
        foreach ($agNegativeKeywords as $adGroupId => $keywords) {
            $kwArr[] = array('AdGroupId' => $adGroupId, 'NegativeKeywords' => $keywords);
        }
        $params['AdGroupNegativeKeywords'] = $kwArr;

        return $this->execute('SetNegativeKeywordsToAdGroups', $params);
    }

    // -- CAMPAIGNS

    /**
     *
     * @param int $accountId Account ID
     * @param array $cNegativeKeywords Associative Array of CampaignID => array(negative keywords)s
     * @return mixed default response type (Object, Array or Raw XML)
     */
    public function setNegativeToCampaigns($accountId, array $cNegativeKeywords) {
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
    public function getNegativeByCampaignIds($accountId, array $campaignIds) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['CampaignIds'] = $campaignIds;
        return $this->execRespond('GetNegativeKeywordsByCampaignIds', $params);
    }
}
?>