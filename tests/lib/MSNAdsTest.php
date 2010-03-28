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

/**
 *
 * WHEN RUNNING THIS UNIT TEST PLEASE ENSURE CONFIG IS USING SANDBOX MODE
 *
 */

require_once 'PHPUnit/Framework.php';
require_once(dirname(__FILE__).'/../../config.php');

class MSNAdsTest extends PHPUnit_Framework_TestCase {

    private $_obj = NULL;

    public function setUp() {
        $this->_obj = new MSNAds();
        $this->_obj->setResponseDefault(MSNAdCenter::RESPONSE_ARRAY);
    }

    public function tearDown() {
    }

    public function testAdd() {
        $ad = $this->_obj;

        $newAd = array();
        $newAd['Title'] = 'ad title';
        $newAd['DisplayUrl'] = $newAd['DestinationUrl'] = 'http://www.foobalicious.com';
        $newAd['Text'] = 'ad text';

        $response = $ad->add(123, array($newAd));
        $this->assertArrayHasKey('AdIds', $response);
    }

    public function testDelete() {
        $ad = $this->_obj;
        $this->assertTrue($ad->delete(123, array(10)));
    }

    public function testUpdate() {
        $ad = $this->_obj;
        $this->assertTrue($ad->update(123, array(10)));
    }

    public function testPause() {
        $ad = $this->_obj;
        $this->assertTrue($ad->pause(123, array(10)));
    }

    public function testResume() {
        $ad = $this->_obj;
        $this->assertTrue($ad->resume(123, array(10)));
    }

    public function testGetByIds() {
        $ad = $this->_obj;
        $response = $ad->getByIds(123, array(10, 20, 30));
        $this->assertArrayHasKey('Ads', $response);
    }

    // -------------------------------------------------------------------------

    public function testGetByEditorialStatus() {
        $ad = $this->_obj;
        $response = $ad->getByEditorialStatus(123, 'Active');
        $this->assertArrayHasKey('Ads', $response);
    }

    public function testGetByAdGroupId() {
        $ad = $this->_obj;
        $response = $ad->getByAdGroupId(123);
        $this->assertArrayHasKey('Ads', $response);
    }
}
?>