<?php
class MSNCampaigns extends MSNAdCenter {

    const NAME = 'Campaigns';

    public static $struct = array('BroadMatchBid',
                                        'CashBackInfo',
                                        'ConversionTrackingEnabled',
                                        'ConversionTrackingScript',
                                        'DailyBudget',
                                        'DaylightSaving',
                                        'Description',
                                        'Id',
                                        'MonthlyBudget',
                                        'Name',
                                        'NegativeKeywords',
                                        'NegativeSiteUrls',
                                        'Status',
                                        'TimeZone',
                                    );

       public function __call($service, $params) {
        $action = '';
        switch ($service) {
            case 'GetAdsByEditorialStatus' :
                $action = $service;
                break;
            default: break;
        }

        if ($action !== '') {
            $this->_params = $params;
            return $this->execute($action);
        } else {
            return parent::__call($service, $params);
        }
    }

}

?>
