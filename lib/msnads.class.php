<?php
class MSNAds extends MSNAdCenter {
    const NAME = 'Ads';

    // Ad Structure helper
    // can be populated and passed back to createAd
    private $_objStruct = array(
        'Title' => NULL,
        'DestinationUrl' => NULL,
        'DisplayUrl' => NULL,
        'Text' => NULL
    );

    public function getObjStruct() {
        return $this->_objStruct;
    }

    private function structExec($service, $adGroupID, array $ads) {
        $params = array();
        $params['AdGroupId'] = $adGroupID;
        $adSVar = array();
        foreach ($ads as $adStruct) {
            $adSVar[] = new SoapVar($adStruct, SOAP_ENC_OBJECT, 'TextAd', $this->_xmlns);
        }
        $params['Ads'] = array('Ad' => $adSVar);
        return $this->execute($service, $params);
    }

    public function add($adGroupID, array $ads) {
        return $this->structExec('AddAds', $adGroupID, $ads);
    }

    public function update($adGroupID, array $ads) {
        return $this->structExec('UpdateAds', $adGroupID, $ads);
    }

    private function statusExec($service, $adGroupID, array $adIds) {
        $params = array();
        $params['AdGroupId'] = $adGroupID;
        foreach ($adIds as $adid) {
            // @todo exception here?
            if ($service == 'UpdateAds' && empty($adid['Id'])) return FALSE;
            $params['AdIds'][] = $adid;
        }
        return $this->execute($service, $params);        
    }

    public function delete($adGroupID, array $adIds) {
        return $this->statusExec('DeleteAds', $adGroupID, $adIds);
    }

    public function pause($adGroupID, array $adIds) {
        return $this->statusExec('PauseAds', $adGroupID, $adIds);
    }

    public function resume($adGroupID, array $adIds) {
        return $this->statusExec('ResumeAds', $adGroupID, $adIds);
    }

    public function getByIds($adGroupID, array $adIds) {
        return $this->statusExec('GetAdsByIds', $adGroupID, $adIds);
    }

    public function getByEditorialStatus($adGroupID, $editorialStatus) {
        $params = array();
        $params['AdGroupId'] = $adGroupID;
        $params['EditorialStatus'] = $editorialStatus;
        return $this->execute('GetAdsByEditorialStatus', $params);
    }
}
?>