<?php
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

    public function getObjStruct() {
        return $this->_objStruct;
    }

    private function structExec($service, $accountId, array $campaigns) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['Campaigns'] = array('Keyword' => $campaigns);
        return $this->execute($service, $params);
    }

    public function add($accountId, array $campaigns) {
        return $this->structExec('AddCampaigns', $accountId, $campaigns);
    }

    public function update($accountId, array $campaigns) {
        return $this->structExec('UpdateCampaigns', $accountId, $campaigns);
    }

    private function statusExec($service, $accountId, array $campaignIds) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['CampaignIds'] = $campaignIds;
        return $this->execute($service, $params);
    }

    public function delete($accountId, array $campaignIds) {
        return $this->statusExec('DeleteCampaigns', $accountId, $campaignIds);
    }

    public function pause($accountId, array $campaignIds) {
        return $this->statusExec('PauseCampaigns', $accountId, $campaignIds);
    }

    public function resume($accountId, array $campaignIds) {
        return $this->statusExec('ResumeCampaigns', $accountId, $campaignIds);
    }

    public function getByIds($accountId, array $campaignIds) {
        return $this->statusExec('GetCampaignsByIds', $accountId, $campaignIds);
    }

    // -------------------------------------------------------------------------

    public function getByAccountId($accountId) {
        $params = array();
        $params['AccountId'] = $accountId;
        return $this->execute('GetCampaignsByAccountID', $params);
    }

    public function getInfoByAccountId($accountId) {
        $params = array();
        $params['AccountId'] = $accountId;
        return $this->execute('GetCampaignsInfoByAccountID', $params);
    }

    // -- KEYWORDS
    public function setNegativeKeywords($accountId, array $cNegativeKeywords) {
        $params = array();
        $params['AccountId'] = $accountId;
        $cArr = array();
        foreach ($cNegativeKeywords as $campaignId => $keywords) {
            $kwArr[] = array('CampaignId' => $adGroupId, 'NegativeKeywords' => $keywords);
        }
        $params['CampaignNegativeKeywords'] = $kwArr;

        return $this->execute('SetNegativeKeywordsToCampaigns', $params);
    }

    public function getNegativeKeywords($accountId, array $campaignIds) {
        $params = array();
        $params['AccountId'] = $accountId;
        $params['CampaignIds'] = $campaignIds;
        return $this->execute('GetNegativeKeywordsByCampaignIds', $params);
    }

    // -- TARGETS
    public function getTargets(array $campaignIds) {
        $params = array();
        $params['CampaignIds'] = $campaignIds;
        return $this->execute('GetTargetsByCampaignIds', $params);
    }

    // -- ADGROUPS
    public function getAdGroups($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $this->execute('GetAdGroupsByCampaignId', $params);
    }

    public function getAdGroupsInfo($campaignId) {
        $params = array();
        $params['CampaignId'] = $campaignId;
        $this->execute('GetAdGroupsInfoByCampaignId', $params);
    }
}
?>