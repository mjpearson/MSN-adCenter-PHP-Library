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
 * @internal  $Id: msnads.class.php 8 2010-03-29 08:38:50Z mjpearson $
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
class MSNTargets extends MSNAdCenter {
    const NAME = 'Targets';

    // Ads use a different namespace
    static protected $_xmlns = 'https://adcenter.microsoft.com/v6';
    // Ad Structure helper
    static private $_objStruct = array(
    );

    /**
     *
     * @link http://msdn.microsoft.com/en-us/library/cc817316.aspx
     * @param string $service MSN AdCenter Service Operation
     * @param array $targets Array of 'Target' structures
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    static private function structExec($service, array $targets, $boolResponse = FALSE) {
        $params = array(
            'Targets' => array('Target' => $targets),
        );

        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     * Adds a target collection of a single type to a library
     * @param array $targets Array of 'Target' structures
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function addToLibrary(array $targets) {
        return self::structExec('AddTargetsToLibrary', $targets);
    }

    /**
     * Adds a target collection of a single type to a library
     * @param array $targets Array of 'Target' structures
     * @return mixed default response type (Object, Array or Raw XML)
     */
    static public function updateInLibrary(array $targets) {
        return self::structExec('UpdateTargetsInLibrary', $targets, TRUE);
    }

    /**
     * Performs an operation on an existing library
     * @param string $service MSDN AdCenter Service Operation
     * @param array $targetIds Target Id longs
     * @param bool $boolResponse return operation status only
     * @return mixed default response type (Object, Array or Raw XML), or bool if $boolResponse == TRUE
     */
    static private function statusExec($service, array $targetIds, $boolResponse = FALSE) {
        $params = array();
        $params['TargetIds'] = $targetIds;

        if ($boolResponse) {
            return self::execute($service, $params);
        } else {
            return self::execRespond($service, $params);
        }
    }

    /**
     * Deletes the target ids
     * @param array $targetIds Target Id longs
     * @return bool operation completed (TrackingID is in response header)
     */
    static public function delete(array $targetIds) {
        return self::statusExec('DeleteTargetsFromLibrary', $targetIds, TRUE);
    }

     /**
     * Retrieves all targets of given target ids
     * @param array $targetIds Target Id longs
     * @return array Target associative array structure, keyed to targetid's
     */
    static public function getByIds(array $targetIds) {
        return self::statusExec('GetTargetsByIds', $targetIds);
    }
}


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

    private $properties = array();

    public function __construct() {
        $this->properties = $this->getProperties();
    }

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