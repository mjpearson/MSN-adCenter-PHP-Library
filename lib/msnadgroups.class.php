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
        foreach ($adGroupIds as $adid) {
            $params['AdIds'][] = $adid;
        }
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

    public function submitForApproval($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $this->execute('SubmitAdGroupForApproval', $params);
    }

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

    public function getSitePlacementsByAdGroupId($adGroupId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        return $this->execute('GetSitePlacementsByAdGroupId', $params);
    }

    public function setTargetTo($adGroupId, $targetId) {
        $params = array();
        $params['AdGroupId'] = $adGroupId;
        $params['TargetId'] = $targetId;
        return $this->execute('SetTargetToAdGroup', $params);
    }
}
?>