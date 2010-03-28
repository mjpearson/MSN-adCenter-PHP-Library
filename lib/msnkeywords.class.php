<?php
class MSNKeywords extends MSNAdCenter {

    const NAME = 'Keywords';

    public static $struct = array('BroadMatchBid',
                                        'CashBackInfo',
                                        'ContentMatchBid',
                                        'EditorialStatus',
                                        'ExactMatchBid',
                                        'Id',
                                        'NegativeKeywords',
                                        'OverridePriority',
                                        'Param1',
                                        'Param2',
                                        'Param3',
                                        'PhraseMatchBid',
                                        'Status',
                                        'Text'
                                    );

    private $_serviceMethods = array(
                                        'GetKeywordsByEditorialStatus',
                                        'GetKeywordQualityScoresByIds'
                                    );

    private $_relations = array(
                                    'AdGroups' => NULL,
                                    'Campaigns' => NULL
                                    );

    public function __construct($noProxy) {
        foreach ($this->_relations as $rClass) {
            
        }
        
    }

    public function __call($service, $params) {
        if (in_array($service, $this->_serviceMethods)) {
            $this->_params = $params;
            return $this->execute($service);
        }
    }

    Relations :
        AdGroups :
            Get[]ByAdGroupId
            GetNegative[]ByAdGroupIds
            SetNegative[]ToAdGroups

        Campaigns :
            SetNegative[]ToCampaigns
            GetNegative[]ByCampaignIds
}

?>
