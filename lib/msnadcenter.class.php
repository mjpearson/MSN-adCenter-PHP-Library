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
class MSNAdCenter {

    const RESPONSE_OBJ = 0;

    const RESPONSE_ARRAY = 1;

    const RESPONSE_XML = 2;

    static protected $_headers = array();

    //static protected $_xmlns = "https://adcenter.microsoft.com/v6";
    static protected $_xmlns = "http://adcenter.microsoft.com/syncapis";


    static protected $_opts = array(
            'trace' => TRUE,
            'location' => MSDNAPI_SERVICE_URL
    );

    static protected $_client = NULL;

    static protected $_response = NULL;

    static protected $_responseHeaders = NULL;

    static protected $_responseDefault = self::RESPONSE_ARRAY;

    static public $debug;

    static public $lastError = array();

    /**
     * The MSN API contructor
     *
     * @param Boolean $debug - Whether or not debugging output is displayed
     * @param String $debug_style - Options are "cli" or "html". All it does is print "\n" or "<br>" for debugging output.
     * @param SOAPClient $client optional overriding soapclient object
     * @param array $headers optional overriding headers
     */
    static public function setUp($debug_enabled = FALSE, $debug_style = 'cli', $client = NULL, $headers = NULL) {
        self::$debug['enabled'] = $debug_enabled;
        self::$debug['style'] = $debug_style;
        self::$debug['last_response'] = 0;

        //Create the input headers
        if ($headers !== NULL) {
            self::$_headers = $headers;
        } else {
            self::$_headers[] = new SoapHeader(self::$_xmlns, 'ApplicationToken',API_KEY,false);
            self::$_headers[] = new SoapHeader(self::$_xmlns, 'DeveloperToken',API_KEY_DEV,false);
            self::$_headers[] = new SoapHeader(self::$_xmlns, 'UserName',API_USER,false);
            self::$_headers[] = new SoapHeader(self::$_xmlns, 'Password',API_PASSWORD,false);
            self::$_headers[] = new SoapHeader(self::$_xmlns, 'CustomerAccountId',API_CUSTOMER_ID,false);
        }

        if ($client !== NULL) {
            self::$_client = $client;
        } else {
            self::$_client = new SOAPClient(MSDNAPI_SERVICE_URL.'?wsdl', self::$_opts);
        }
    }

    static public function setResponseDefault($responseType) {
        switch($responseType) {
            case self::RESPONSE_ARRAY :
            case self::RESPONSE_OBJ :
            case self::RESPONSE_XML :
                self::$_responseDefault = $responseType;
                break;
            default:
                throw new RuntimeException('Invalid response type selected');
                break;
        }
    }

    static public function getResponseDefault() {
        return self::$_responseDefault;
    }

    static protected function getServiceName() {
        return constant(get_class($this).'::NAME');
    }

    static public function setResponse($response) {
        self::$_response = $response;
    }

    static public function getRequestHeaders() {
        return self::$_headers;
    }

    static public function getClient() {
        return self::$_client;
    }

    static public function setClient($client) {
        self::$_client = $client;
    }

    static public function obj2Arr($obj) {
        if (!is_object($obj) && !is_array($obj)) {
            return $obj;
        } else if (is_object($obj)) {
            $obj = get_object_vars($obj);
        }

        return array_map(array('MSNAdCenter', 'obj2Arr'), $obj);
    }

    static public function getResponse($responseType = self::RESPONSE_XML) {
        if ($responseType == self::RESPONSE_OBJ) {
            return self::$_response;
        } else if ($responseType == self::RESPONSE_XML) {
            return self::$_client->__getLastResponse();
        } else if ($responseType == self::RESPONSE_ARRAY) {
            return self::obj2Arr(self::$_response);
        }
    }

    static protected function setResponseHeaders($responseHeaders) {
        self::$_responseHeaders = $responseHeaders;
    }

    static public function getResponseHeaders() {
        return self::$_responseHeaders;
    }

    /**
     * Execute a SOAP call to MSN adCenter API
     *
     * @param String $action - Action to perform on your service
     * @param Array $params - Parameters to send with the action
     * @param String $fetch_as - Either array or xml for the return object
     * @return Respose
     */
    static protected function execute($action, $params) {
        self::debug_print("------------------ execute ------------------");
        //self::debug_print("SERVICE: '".self::getServiceName()."'"); //
        self::debug_print("ACTION: '".$action."'");

        if (self::$debug['style'] == 'cli') {
            self::debug_print("PARAMS: '".print_r($params,true)."'");
        } else {
            self::debug_print("PARAMS: '<pre>".print_r($params,true)."</pre>'");
        }

        try {
            $output_headers = array();
            $request = array($action.'Request' => $params);

            $result = self::$_client->__soapCall(
                    $action,
                    $request,
                    null,
                    self::$_headers,
                    $output_headers);

            $c = self::$_client;

            self::setResponse($result);
            self::setResponseHeaders($output_headers);
            return TRUE;
        } catch (Exception $e) {
            self::process_errors($e);
        }
        return FALSE;
    }

    static public function execRespond($service, $params) {
        if (self::execute($service, $params)) {
            return self::getResponse(self::$_responseDefault);
        }
        return NULL;
    }

    /**
     * Process errors encountered by execute
     *
     * @param Object $e - Exception generated
     * @return None
     */
    private function process_errors($e) {
        self::debug_print("ERROR ON LAST EXECUTE!");

        if (isset($e->detail->ApiFaultDetail)) {
            self::debug_print("ApiFaultDetail exception encountered");
            self::debug_print("Tracking ID: ".$e->detail->ApiFaultDetail->TrackingId);

            // Process any operation errors.
            if (isset($e->detail->ApiFaultDetail->OperationErrors->OperationError)) {
                if (is_array($e->detail->ApiFaultDetail->OperationErrors->OperationError)) {
                    // An array of operation errors has been returned.
                    $obj = $e->detail->ApiFaultDetail->OperationErrors->OperationError;
                }
                else {
                    // A single operation error has been returned.
                    $obj = $e->detail->ApiFaultDetail->OperationErrors;
                }
                
                foreach ($obj as $operationError) {
                    self::debug_print("Operation error encountered:");
                    self::debug_print("Message: ".$operationError->Message);
                    self::debug_print("Details: ".$operationError->Details);
                    self::debug_print("ErrorCode: ".$operationError->ErrorCode);
                    self::debug_print("Code: ".$operationError->Code);
                }                
            }

            // Process any batch errors.
            if (isset($e->detail->ApiFaultDetail->BatchErrors->BatchError)) {
                if (is_array($e->detail->ApiFaultDetail->BatchErrors->BatchError)) {
                    // An array of batch errors has been returned.
                    $obj = $e->detail->ApiFaultDetail->BatchErrors->BatchError;
                }
                else {
                    // A single batch error has been returned.
                    $obj = $e->detail->ApiFaultDetail->BatchErrors;
                }
                foreach ($obj as $batchError) {
                    self::debug_print("Batch error encountered for array index ".$batchError->Index);
                    self::debug_print("Message: ".$batchError->Message);
                    self::debug_print("Details: ".$batchError->Details);
                    self::debug_print("ErrorCode: ".$batchError->ErrorCode);
                    self::debug_print("Code: ".$batchError->Code);
                }
            }
        }

        if (isset($e->detail->AdApiFaultDetail)) {
            self::debug_print("AdApiFaultDetail exception encountered");
            self::debug_print("Tracking ID: ".$e->detail->AdApiFaultDetail->TrackingId);

            // Process any operation errors.
            if (isset($e->detail->AdApiFaultDetail->Errors)) {
                if (is_array($e->detail->AdApiFaultDetail->Errors)) {
                    // An array of errors has been returned.
                    $obj = $e->detail->AdApiFaultDetail->Errors;
                }
                else {
                    // A single error has been returned.
                    $obj = $e->detail->AdApiFaultDetail->Errors;
                }
                foreach ($obj as $Error) {
                    self::debug_print("Error encountered:");
                    self::debug_print("Message: ".$Error->Message);
                    self::debug_print("Detail: ".$Error->Detail);
                    self::debug_print("ErrorCode: ".$Error->ErrorCode);
                    self::debug_print("Code: ".$Error->Code);
                }
            }
        }

        // Display the fault code and the fault string.
        self::debug_print($e->faultcode." ".$e->faultstring);
        self::debug_print(self::$_client->__getLastRequestHeaders());
        self::debug_print(self::$_client->__getLastRequest());
    }

    /**
     * Print debugging output
     *
     * @param String $string - String to print
     */
    private function debug_print($string) {
        if(self::$debug['enabled']) {
            $line_end = "\n";
            if (self::$debug['style'] == 'html')
                $line_end = "<br>";
            print "MSN API Debug: $string{$line_end}";            
        }
        self::$lastError[] = $string;
    }
}

// point spl @ our install path
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
spl_autoload_extensions(implode(',',
        array_merge(
        explode(',', spl_autoload_extensions()),
        array('.class.php'))
));
spl_autoload_register();

// 'Target' data structure helpers
// @link http://msdn.microsoft.com/en-US/library/aa982962%28v=MSADS.60%29.aspx

class Targets  {
    public $Age = array('Bids' => array());
    public $Day = array('Bids' => array());
    public $Gender = array('Bids' => array());
    public $Hour = array('Bids' => array());
    public $Id = NULL;
    public $Location = array('Bids' => array());
}

abstract class TargetBid {

    public $name = NULL;

    // Bid helper
    static public $incrementalBids = array(
                                            'ZeroPercent',
                                            'TenPercent',
                                            'TwentyPercent',
                                            'ThirtyPercent',
                                            'FortyPercent',
                                            'FiftyPercent',
                                            'SixtyPercent',
                                            'SeventyPercent',
                                            'EightyPercent',
                                            'NinetyPercent',
                                            'OneHundredPercent',
                                            'NegativeTenPercent',
                                            'NegativeTwentyPercent',
                                            'NegativeThirtyPercent',
                                            'NegativeFortyPercent',
                                            'NegativeFiftyPercent',
                                            'NegativeSixtyPercent',
                                            'NegativeSeventyPercent',
                                            'NegativeEightyPercent',
                                            'NegativeNinetyPercent',
                                            'NegativeOneHundredPercent'
                                        );

    static public $enumFields = array();

    protected $IncrementalBid = NULL;

    public function __set($attribute, $value) {
        // Perform some enumerated type checking
        if ($attribute == 'IncrementalBid' && !in_array($value, self::$incrementalBids)) {
            throw new RuntimeException($newBid.' not found in TargetBid::$incrementablBids');
        } elseif (array_key_exists($attribute, self::$enumFields) && !in_array($attribute, self::$enumFields[$attribute])) {
                throw new RuntimeException($value.' not found in '.get_class($this).'::$enumFields['.$attribute.']');
        }

        $this->$attribute = $value;
    }

    public function __get($attribute) {
        return $this->$attribute;
    }

    public function getProperties() {
        $reflect = new ReflectionClass($this);
        return $reflect->getProperties(ReflectionProperty::IS_PROTECTED);
    }

}

// @link http://msdn.microsoft.com/en-US/library/bb671720(v=MSADS.60).aspx
class AgeTargetBid extends TargetBid {
    public $name = 'Age';
    protected $Age = NULL;
    static public $enumFields = array('Age' => array(
                                                    'EighteenToTwentyFive',
                                                    'TwentyFiveToThirtyFive',
                                                    'ThirtyFiveToFifty',
                                                    'FiftyToSixtyFive',
                                                    'SixtyFiveAndAbove'
                                                ));
}

class BehaviouralTargetBid extends TargetBid {
    public $name = 'Behavioural';
    protected $BehavioralName = NULL;
}

class GenderTargetBid extends TargetBid {
    public $name = 'Gender';
    protected $Gender = NULL;
    static public $enumFields = array('Gender' => array('Male', 'Female'));
}

class DayTargetBid extends TargetBid {
    public $name = 'Day';
    protected $Day = NULL;
    static public $enumFields = array('Day' => array(
                                                    'Sunday',
                                                    'Monday',
                                                    'Tuesday',
                                                    'Wednesday',
                                                    'Thursday',
                                                    'Friday',
                                                    'Saturday'
                                                ));
}

class HourTargetBid extends TargetBid {
    public $name = 'Hour';
    protected $Hour = NULL;   
    static public $enumFields = array('Hour' => array(
                                                    'ThreeAMToSevenAM',
                                                    'SevenAMToElevenAM',
                                                    'ElevenAMToTwoPM',
                                                    'TwoPMToSixPM',
                                                    'SixPMToElevenPM',
                                                    'ElevenPMToThreeAM'
                                                ));
}

class LocationTargetBid extends TargetBid {
    protected $BusinessTarget = NULL;
    protected $CityTarget = NULL;
    protected $CountryTarget = NULL;
    protected $MetroAreaTarget = NULL;
    protected $RadiusTarget = NULL;
    protected $StateTarget = NULL;
    protected $TargetAllLocations = FALSE;
}

// ----------- LocationTargetBid types
class CityTargetBid extends TargetBid {
    public $name = 'City';
    protected $City = NULL;
}

class CountryTargetBid extends TargetBid {
    public $name = 'Country';
    protected $CountryAndRegion = NULL;
}

class StateTargetBid extends TargetBid {
    public $name = 'State';
    protected $State = NULL;
}

class MetroAreaTargetBid extends TargetBid {
    public $name = 'MetroArea';
    protected $MetroArea = NULL;
}

class RadiusTargetBid extends TargetBid {
    public $name = 'RadiusTarget';
    protected $LatitudeDegrees = NULL;
    protected $LongitudeDegrees = NULL;
    protected $Radius = NULL;
}
?>