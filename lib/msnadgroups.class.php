<?php
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

    public function getObjStruct() {
        return $this->_objStruct;
    }

    private function structExec($service, $campaignId, array $adGroups) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroups'] = array('AdGroup' => $adGroups);
        return $this->execute($service, $params);
    }

    public function add($campaignId, array $adGroups) {
        return $this->structExec('AddAdGroups', $campaignId, $adGroups);
    }

    public function update($campaignId, array $adGroups) {
        return $this->structExec('UpdateAdGroups', $campaignId, $adGroups);
    }

    private function statusExec($service, $campaignId, array $adGroupIds) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        return $this->execute($service, $params);
    }

    public function delete($campaignId, array $adGroupIds) {
        return $this->statusExec('DeleteAdGroups', $campaignId, $adGroupIds);
    }

    public function pause($campaignId, array $adGroupIds) {
        return $this->statusExec('PauseAdGroups', $campaignId, $adGroupIds);
    }

    public function resume($campaignId, array $adGroupIds) {
        return $this->statusExec('ResumeAdGroups', $campaignId, $adGroupIds);
    }

    public function getByIds($campaignId, array $adGroupIds) {
        return $this->statusExec('GetAdGroupsByIds', $campaignId, $adGroupIds);
    }

    // -------------------------------------------------------------------------

    public function submitForApproval($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $this->execute('SubmitAdGroupForApproval', $params);
    }

    // -- CAMPAIGN

    public function getByCampaignId($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return $this->execute('GetAdGroupsByCampaignId', $params);
    }

    public function getInfoByCampaignId($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        return $this->execute('GetAdGroupsInfoByCampaignId', $params);
    }

    // -- ADS

    public function getAds($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupID;
        return $this->execute('GetAdsByAdGroupId', $params);
    }

    // -- SITEPLACEMENTS

    public function getSitePlacements($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('GetSitePlacementsByAdGroupId', $params);
    }

    // -- TARGETS

    public function setTargetTo($adGroupId, $targetId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['TargetId'] = $targetId;
        return $this->execute('SetTargetToAdGroup', $params);
    }

    public function getTargets(array $adGroupIds) {
        $params = array();
        $params['AdGroupIds'] = $adGroupIds;
        return $this->execute('GetTargetsByAdGroupIds', $params);
    }

    public function deleteTarget($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('DeleteTargetFromAdGroup', $params);
    }

    // -- KEYWORDS

    public function getKeywords($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('GetKeywordsByAdGroupId', $params);
    }

    public function getNegativeKeywords($campaignId, array $adGroupIds) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $params['AdGroupIds'] = $adGroupIds;
        return $this->execute('GetNegativeKeywordsByAdGroupIds', $params);
    }

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

    public function getBehaviouralBids($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('GetBehaviouralBidsByAdGroupId', $params);
    }
}
?>