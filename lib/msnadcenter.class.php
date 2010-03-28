<?php
/**
 * An easy to use MSN adCenter PHP Library
 *
 * @name      MSN adCenter PHP Library
 * @author    Michael Taggart <mtaggart@envoymediagroup.com>
 * @author    Michael Pearson <michael@cloudspark.com.au>
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
abstract class MSNAdCenter {

    const RESPONSE_OBJ = 0;

    const RESPONSE_ARRAY = 1;

    const RESPONSE_XML = 2;

    protected $_headers = array();

    protected $_xmlns = "https://adcenter.microsoft.com/v6";

    protected $_opts = array(
            'trace' => TRUE,
            'location' => MSDNAPI_SERVICE_URL,
    );

    protected $_client = NULL;

    protected $_response = NULL;

    protected $_responseHeaders = NULL;

    protected $_responseDefault = self::RESPONSE_ARRAY;

    public $debug;

    /**
     * The MSN API contructor
     *
     * @param Boolean $debug - Whether or not debugging output is displayed
     * @param String $debug_style - Options are "cli" or "html". All it does is print "\n" or "<br>" for debugging output.
     * @param SOAPClient $client optional overriding soapclient object
     * @param array $headers optional overriding headers
     */
    public function __construct($debug_enabled = FALSE, $debug_style = 'cli', $client = NULL, $headers = NULL) {
        $this->debug['enabled'] = $debug_enabled;
        $this->debug['style'] = $debug_style;
        $this->debug['last_response'] = 0;

        //Create the input headers
        if ($headers !== NULL) {
            $this->_headers = $headers;
        } else {
            $this->_headers[] = new SoapHeader($this->_xmlns, 'ApplicationToken',API_KEY,false);
            $this->_headers[] = new SoapHeader($this->_xmlns, 'DeveloperToken',API_KEY_DEV,false);
            $this->_headers[] = new SoapHeader($this->_xmlns, 'UserName',API_USER,false);
            $this->_headers[] = new SoapHeader($this->_xmlns, 'Password',API_PASSWORD,false);
            $this->_headers[] = new SoapHeader($this->_xmlns, 'CustomerAccountId',API_CUSTOMER_ID,false);
        }

        if ($client !== NULL) {
            $this->_client = $client;
        } else {
            $this->_client = new SOAPClient(MSDNAPI_SERVICE_URL.'?wsdl', $this->_opts);
        }
    }

    public function setResponseDefault($responseType) {
        switch($responseType) {
            case self::RESPONSE_ARRAY :
            case self::RESPONSE_OBJ :
            case self::RESPONSE_XML :
                $this->_responseDefault = $responseType;
                break;
            default:
                throw new RuntimeException('Invalid response type selected');
                break;
        }
    }

    public function getResponseDefault() {
        return $this->_responseDefault;
    }

    protected function getServiceName() {
        return constant(get_class($this).'::NAME');
    }

    public function setResponse($response) {
        $this->_response = $response;
    }

    public function getRequestHeaders() {
        return $this->_headers;
    }

    public function getClient() {
        return $this->_client;
    }

    public function obj2Arr($obj) {
        if (!is_object($obj) && !is_array($obj)) {
            return $obj;
        } else if (is_object($obj)) {
            $obj = get_object_vars($obj);
        }

        return array_map(array($this, 'obj2Arr'), $obj);
    }

    public function getResponse($responseType = self::RESPONSE_XML) {
        if ($responseType == self::RESPONSE_OBJ) {
            return $this->_response;
        } else if ($responseType == self::RESPONSE_XML) {
            return $this->_client->__getLastResponse();
        } else if ($responseType == self::RESPONSE_ARRAY) {
            return $this->obj2Arr($this->_response);
        }
    }

    protected function setResponseHeaders($responseHeaders) {
        $this->_responseHeaders = $responseHeaders;
    }

    public function getResponseHeaders() {
        return $this->_responseHeaders;
    }

    /**
     * Execute a SOAP call to MSN adCenter API
     *
     * @param String $action - Action to perform on your service
     * @param Array $params - Parameters to send with the action
     * @param String $fetch_as - Either array or xml for the return object
     * @return Respose
     */
    protected function execute($action, $params) {
        $this->debug_print("------------------ execute ------------------");
        $this->debug_print("SERVICE: '".$this->getServiceName()."'");
        $this->debug_print("ACTION: '".$action."'");

        //print_r($params); exit;

        if ($this->debug['style'] == 'cli') {
            $this->debug_print("PARAMS: '".print_r($params,true)."'");
        } else {
            $this->debug_print("PARAMS: '<pre>".print_r($params,true)."</pre>'");
        }

        try {
            $output_headers = array();

            $request = array($action.'Request' => $params);

            $result = $this->_client->__soapCall(
                    $action,
                    $request,
                    null,
                    $this->_headers,
                    $output_headers);

            $this->setResponse($result);
            $this->setResponseHeaders($output_headers);
            return TRUE;
        } catch (Exception $e) {
            $this->process_errors($e);
        }
        return FALSE;
    }


    public function execRespond($service, $params) {
        if ($this->execute($service, $params)) {
            return $this->getResponse($this->_responseDefault);
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
        $this->debug_print("ERROR ON LAST EXECUTE!");

        if (isset($e->detail->ApiFaultDetail)) {
            $this->debug_print("ApiFaultDetail exception encountered");
            $this->debug_print("Tracking ID: ".$e->detail->ApiFaultDetail->TrackingId);

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
                    $this->debug_print("Operation error encountered:");
                    $this->debug_print("Message: ".$operationError->Message);
                    $this->debug_print("Details: ".$operationError->Details);
                    $this->debug_print("ErrorCode: ".$operationError->ErrorCode);
                    $this->debug_print("Code: ".$operationError->Code);
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
                    $this->debug_print("Batch error encountered for array index ".$batchError->Index);
                    $this->debug_print("Message: ".$batchError->Message);
                    $this->debug_print("Details: ".$batchError->Details);
                    $this->debug_print("ErrorCode: ".$batchError->ErrorCode);
                    $this->debug_print("Code: ".$batchError->Code);
                }
            }
        }

        if (isset($e->detail->AdApiFaultDetail)) {
            $this->debug_print("AdApiFaultDetail exception encountered");
            $this->debug_print("Tracking ID: ".$e->detail->AdApiFaultDetail->TrackingId);

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
                    $this->debug_print("Error encountered:");
                    $this->debug_print("Message: ".$Error->Message);
                    $this->debug_print("Detail: ".$Error->Detail);
                    $this->debug_print("ErrorCode: ".$Error->ErrorCode);
                    $this->debug_print("Code: ".$Error->Code);
                }
            }
        }

        // Display the fault code and the fault string.
        $this->debug_print($e->faultcode." ".$e->faultstring);
    }

    /**
     * Print debugging output
     *
     * @param String $string - String to print
     */
    private function debug_print($string) {
        if($this->debug['enabled']) {
            $line_end = "\n";
            if ($this->debug['style'] == 'html')
                $line_end = "<br>";
            print "MSN API Debug: $string{$line_end}";
        }
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
?>