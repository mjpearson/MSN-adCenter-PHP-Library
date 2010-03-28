<?php
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

    public function getObjStruct() {
        return $this->_objStruct;
    }

    private function structExec($service, $adGroupId, array $keywords) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['Keywords'] = array('Keyword' => $keywords);
        return $this->execute($service, $params);
    }

    public function add($adGroupId, array $keywords) {
        return $this->structExec('AddKeywords', $adGroupId, $keywords);
    }

    public function update($adGroupId, array $keywords) {
        return $this->structExec('UpdateKeywords', $adGroupId, $keywords);
    }

    private function statusExec($service, $adGroupId, array $keywordIds) {
        $params = array();
        $params['CampaignId'] = $adGroupId;
        $params['KeywordIds'] = $keywordIds;
        return $this->execute($service, $params);
    }

    public function delete($adGroupId, array $keywordIds) {
        return $this->statusExec('DeleteKeywords', $adGroupId, $keywordIds);
    }

    public function pause($adGroupId, array $keywordIds) {
        return $this->statusExec('PauseKeywords', $adGroupId, $keywordIds);
    }

    public function resume($adGroupId, array $keywordIds) {
        return $this->statusExec('ResumeKeywords', $adGroupId, $keywordIds);
    }

    public function getByIds($adGroupId, array $keywordIds) {
        return $this->statusExec('GetKeywordsByIds', $adGroupId, $keywordIds);
    }

    // -------------------------------------------------------------------------

    // -- ADGROUPS
    public function getByAdGroupId($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('GetKeywordsByAdGroupId', $params);
    }

    public function getNegativeByAdGroupId($campaignId, array $adGroupIds) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        return $this->execute('GetNegativeKeywordsByAdGroupIds', $params);
    }

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

    public function setNegativeToCampaigns($accountId, array $cNegativeKeywords) {
        $params = array();
        $params['AccountId'] = $accountId;
        $cArr = array();
        foreach ($cNegativeKeywords as $campaignId => $keywords) {
            $kwArr[] = array('CampaignId' => $adGroupId, 'NegativeKeywords' => $keywords);
        }
        $params['CampaignNegativeKeywords'] = $kwArr;

        return $this->execute('SetNegativeKeywordsToCampaigns', $params);
    }

    public function getNegativeByCampaignIds($accountId, array $campaignIds) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['CampaignIds'] = $campaignIds;
        return $this->execute('GetNegativeKeywordsByCampaignIds', $params);
    }
}
?>