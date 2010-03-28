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
 * Example Implementation:
 *
 *
 * Shameless Plug:
 * Envoy Media Group is a marketing company that specializes in PPC, Email, TV,
 * Radio, etc. If you are a solid PHP coder and want to be a part of a fun, vibrant
 * team that tackle complex problems please send your info to mtaggart@envoymediagroup.com
 * We are always looking for talented developers and welcome the opportunity to discuss
 * the possibilities of you joining our team.
 *
 */
abstract class MSNAdCenter {
    
    protected $_headers = array();

    protected $_opts = array(
                                'trace' => TRUE,
                                'location' => MSDNAPI_SERVICE_URL,
                            );

    protected $_client = NULL;

    protected $_xmlns = "https://adcenter.microsoft.com/v6";

    protected $_response = NULL;

    protected $_responseHeaders = NULL;

    var $auth;
    var $api;
    var $debug;

    /**
     * The MSN API contructor
     *
     * @param Boolean $debug - Whether or not debugging output is displayed
     * @param String $debug_style - Options are "cli" or "html". All it does is print "\n" or "<br>" for debugging output.
     */
    public function __construct($debug_enabled = FALSE, $debug_style = 'cli') {      
        $this->debug['enabled'] = $debug_enabled;
        $this->debug['style'] = $debug_style;
        $this->debug['last_response'] = 0;

        //Create the input headers
        $this->_headers[] = new SoapHeader($this->_xmlns, 'ApplicationToken',API_KEY,false);
        $this->_headers[] = new SoapHeader($this->_xmlns, 'DeveloperToken',API_KEY_DEV,false);
        $this->_headers[] = new SoapHeader($this->_xmlns, 'UserName',API_USER,false);
        $this->_headers[] = new SoapHeader($this->_xmlns, 'Password',API_PASSWORD,false);
        $this->_headers[] = new SoapHeader($this->_xmlns, 'CustomerAccountId',API_CUSTOMER_ID,false);

        $this->_client = new SOAPClient(MSDNAPI_SERVICE_URL.'?wsdl', $this->_opts);
    }


    public function getStruct() {
        return constant(get_class($this)).'::STRUCT';
    }

    protected function getServiceName() {
        return constant(get_class($this).'::NAME');
    }

    public function setResponse($response) {
        $this->_response = $response;

    }

    const RESPONSE_ARRAY = 1;

    const RESPONSE_XML = 2;

    public function getResponse($responseType = self::RESPONSE_XML) {
        return $this->_response;
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