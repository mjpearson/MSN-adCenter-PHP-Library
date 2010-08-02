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
 * @internal  $Id: msnkeywords.class.php 8 2010-03-29 08:38:50Z mjpearson $
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
class MSNReport extends MSNAdCenter {

    /**
     * Aggregation
     * @link http://msdn.microsoft.com/en-us/library/bb672067%28v=MSADS.60%29.aspx
     */
    const NAME = 'Report';

    const AGG_DAILY = 'Daily';

    const AGG_HOURLY = 'Hourly';

    const AGG_MONTHLY = 'Monthly';

    const AGG_SUMMARY = 'Summary';

    const AGG_WEEKLY = 'Weekly';

    const AGG_YEARLY = 'Yearly';

    static public $aggMap = array();

    /**
     * Periods
     * @link http://msdn.microsoft.com/en-us/library/bb671772%28v=MSADS.60%29.aspx
     */
    const PERIOD_CUSTOMDATES = 'Custom dates';

    const PERIOD_CUSTOMRANGE = 'Custom date range';

    const PERIOD_TODAY = 'Today';

    const PERIOD_YESTERDAY = 'Yesterday';

    const PERIOD_LAST7DAYS = 'LastSevenDays';

    const PERIOD_THISWEEK = 'ThisWeek';

    const PERIOD_LASTWEEK = 'LastWeek';

    const PERIOD_LAST4WEEKS = 'LastFourWeeks';

    const PERIOD_LASTMONTH = 'LastMonth';

    const PERIOD_THISMONTH = 'ThisMonth';

    const PERIOD_LAST3MONTHS = 'LastThreeMonths';

    const PERIOD_LAST6MONTHS = 'LastSixMonths';

    const PERIOD_THISYEAR = 'ThisYear';

    const PERIOD_LASTYEAR = 'LastYear';

    static public $periodMap = array();

    static protected $_xmlns = "https://adcenter.microsoft.com/v6";

    const STATUS_SUCCESS = 'Success';

    const STATUS_PENDING = 'Pending';

    /**
     * Columns
     * @link http://msdn.microsoft.com/en-us/library/bb672087%28v=MSADS.60%29.aspx
     */
    const COLUMN_ACCOUNTNAME = 'AccountName';

    const COLUMN_ACCOUNTNUMBER = 'AccountNumber';

    const COLUMN_ACCOUNTID = 'AccountId';

    const COLUMN_TIMEPERIOD = 'TimePeriod';

    const COLUMN_LANGUAGEANDREGION = 'LanguageAndRegion';

    const COLUMN_CAMPAIGNNAME = 'CampaignName';

    const COLUMN_CAMPAIGNID = 'CampaignId';

    const COLUMN_ADGROUPNAME = 'AdGroupName';

    const COLUMN_ADGROUPID = 'AdGroupId';

    const COLUMN_KEYWORD = 'Keyword';

    const COLUMN_KEYWORDID = 'KeywordId';

    const COLUMN_ADID = 'AdId';

    const COLUMN_ADTYPE = 'AdType';

    const COLUMN_DESTINATIONURL = 'DestinationUrl';

    const COLUMN_CURRENTMAXCPC = 'CurrentMaxCpc';

    const COLUMN_CURRENCYCODE = 'CurrencyCode';

    const COLUMN_MATCHTYPE = 'MatchType';

    const COLUMN_ADDISTRIBUTION = 'AdDistribution';

    const COLUMN_IMPRESSIONS = 'Impressions';

    const COLUMN_CLICKS = 'Clicks';

    const COLUMN_CTR = 'Ctr';

    const COLUMN_AVERAGECPC = 'AverageCpc';

    const COLUMN_SPEND = 'Spend';

    const COLUMN_AVERAGEPOSITION = 'AveragePosition';

    const COLUMN_CONVERSIONS = 'Conversions';

    const COLUMN_CONVERSIONRATE = 'ConversionRate';

    const COLUMN_COSTPERCONVERSION = 'CostPerConversion';

    const COLUMN_AVERAGECPM = 'AverageCpm';

    const COLUMN_PRICINGMODEL = 'PricingModel';

    const COLUMN_CASHBACK = 'Cashback';

    //const COLUMN_BITMATCHTYPE = 'BidMatchType'; // deprecated?

    static public $columnMap = array();

    /**
     * Helper function to build a ReportTime structure
     * @link http://msdn.microsoft.com/en-us/library/bb671816%28v=MSADS.60%29.aspx#Aggregation_Time
     * @param string $periodKey valid time period (MSNReport::PERIOD_* helper)
     * @param array $range either a list of explicit date ranges (yyyy-mm-dd), or an associative array range keyed to 'from' and 'to'
     */
    static public function genTimeStruct($periodKey, array $range = array()) {
        if (!in_array($periodKey, self::$periodMap)) throw new RuntimeException('Invalid Period Type');

        $reportTime = array();

        switch ($periodKey) {
            case self::PERIOD_CUSTOMDATES :
                $dates = array();
                foreach ($range as $date) {
                    list($year, $month, $day) = explode('-', $date);
                    $dates[] = array('Day' => $day, 'Month' => $month, 'Year' => $year);
                }
                $reportTime['CustomDates'] = $dates;
                break;
            case self::PERIOD_CUSTOMRANGE :
                list($year, $month, $day) = explode('-', $range['from']);
                $reportTime['CustomDateRangeStart'] = array('Day' => (int) $day, 'Month' => (int) $month, 'Year' => (int) $year);

                list($year, $month, $day) = explode('-', $range['to']);
                $reportTime['CustomDateRangeEnd'] = array('Day' => (int) $day, 'Month' => (int) $month, 'Year' => (int) $year);
                break;
            default :
                $reportTime['PredefinedTime'] = $periodKey;
                break;
        }

        return $reportTime;
    }

    /**
     *
     * @link http://msdn.microsoft.com/en-us/library/bb671816%28v=MSADS.60%29.aspx#Elements
     * @param string $reportMethod report action
     * @param string $aggregation aggregation type
     * @param array $columns array of columns http://msdn.microsoft.com/en-us/library/bb672087%28v=MSADS.60%29.aspx
     * @param array $scope array of scopes http://msdn.microsoft.com/en-us/library/bb671547%28v=MSADS.60%29.aspx
     * @param <type> $timeStruct
     * @param <type> $filter
     * @return <type>
     */
    static private function _submitReport($reportMethod, $name, $aggregation, $scope, $timeStruct, array $columns = array(), $filter = NULL) {
        if (!in_array($aggregation, self::$aggMap)) throw new RuntimeException('Invalid Aggregation Type');

        if (empty($columns)) {
            $columns = array_values(self::$columnMap);
        }

        $request = array();

        $request['Format'] = 'Xml';
        $request['Language'] = 'English';
        $request['ReportName'] = $name;
        $request['ReturnOnlyCompleteData'] = false;

        $request['Aggregation'] = $aggregation;

        $request['Columns'] = $columns;

        $request['Scope'] = $scope;
        $request['Time'] = $timeStruct;

        if ($filter !== NULL) {
            $request['Filter'] = $filter;
        }        

        $soapstruct = new SoapVar($request,
                              SOAP_ENC_OBJECT,
                              $reportMethod."Request",
                              self::$_xmlns);

        $params = array('ReportRequest' => $soapstruct);

        self::execute('SubmitGenerateReport', $params);

        $response = self::getResponse(self::RESPONSE_OBJ);
        return $response->ReportRequestId;
    }

    
    static public function submitKeywordPerformanceReport($name, $aggregation, $scope, $timeStruct, array $columns = array(), $filter = NULL) {
        return self::_submitReport('KeywordPerformanceReport', $name, $aggregation, $scope, $timeStruct, $columns, $filter);
    }

    static public function submitAdPerformanceReport($name, $aggregation, $scope, $timeStruct, array $columns = array(), $filter = NULL) {
        return self::_submitReport('AdPerformanceReport', $name, $aggregation, $scope, $timeStruct, $columns, $filter);
    }

    /**
     *
     * @param int $requestId report id batched via submit(?)Report
     * @return <type>
     */
    static public function getReport($requestId) {
        $params = array();
        $params['ReportRequestId'] = $requestId;
        self::execute('PollGenerateReport', $params);
        
        $response = self::getResponse(self::RESPONSE_OBJ);
        return $response;
    }
}

// Reflect the report in the absesnse of dtd
$r = new ReflectionClass('MSNReport');
$constants = $r->getConstants();
foreach ($constants as $name => $constant) {
    if (strpos($name, 'AGG_') === 0) {
        MSNReport::$aggMap[] = $constant;
    } elseif (strpos($name, 'PERIOD_') === 0) {
        MSNReport::$periodMap[] = $constant;
    } elseif (strpos($name, 'COLUMN_') === 0) {
        MSNReport::$columnMap[] = $constant;
    }
}
unset($r);
unset($constants);
?>