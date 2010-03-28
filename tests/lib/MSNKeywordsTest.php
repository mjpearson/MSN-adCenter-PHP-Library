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
 * @version   $Rev: 5 $
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

class MSNKeywordsTest extends PHPUnit_Framework_TestCase {

    private $_obj = NULL;

    public function setUp() {
        $this->_obj = new MSNKeywords();
        $this->_obj->setResponseDefault(MSNAdCenter::RESPONSE_ARRAY);
    }

    public function tearDown() {
    }

    public function testKeywordd() {
        $kw = $this->_obj;

        $newKeyword = array(
                'Text' => 'mittens',
                'BroadMatchBid' => array('Amount' => 0.5),
                'ContentMatchBid' => array('Amount' => 0.5),
                'ExactMatchBid' => array('Amount' => 0.5),
                'NegativeKeywords' => NULL
        );

        $response = $kw->add(567, array($newKeyword));
        $this->assertArrayHasKey('KeywordIds', $response);
    }

    public function testDelete() {
        $kw = $this->_obj;
        $this->assertTrue($kw->delete(567, array(10)));
    }

    public function testUpdate() {
        $kw = $this->_obj;
        $this->assertTrue($kw->update(567, array(10)));
    }

    public function testPause() {
        $kw = $this->_obj;
        $this->assertTrue($kw->pause(567, array(10)));
    }

    public function testResume() {
        $kw = $this->_obj;
        $this->assertTrue($kw->resume(567, array(10)));
    }

    public function testGetByIds() {
        $kw = $this->_obj;
        $response = $kw->getByIds(567, array(10, 20, 30));
        $this->assertArrayHasKey('Keywords', $response);
    }

    // -------------------------------------------------------------------------

    // -- ADGROUPS

    public function testGetByAdGroupID() {
        $kw = $this->_obj;
        $response = $kw->getByAdGroupId(123);
        $this->assertArrayHasKey('Keywords', $response);
    }

    public function testGetNegativeByAdGroupId() {
        $kw = $this->_obj;
        $response = $kw->getNegativeByAdGroupId(56789, array(10, 20, 30));
        $this->assertArrayHasKey('AdGroupNegativeKeywords', $response);
    }

    public function testSetNegativeToAdGroups() {
        $kw = $this->_obj;
        $response = $kw->setNegativeToAdGroups(56789, array(10 => array('bad1', 'bad2')));
        $this->assertTrue($response);
    }

    // -- CAMPAIGNS

    public function testSetNegativeToCampaigns() {
        $kw = $this->_obj;
        $response = $kw->setNegativeToCampaigns(987546, array(6667 => array('bad1', 'bad2')));
        $this->assertTrue($response);
    }

    public function testGetNegativeByCampaignIds() {
        $kw = $this->_obj;
        $response = $kw->getNegativeByCampaignIds(987546, array(1, 2, 3, 4));
        $this->assertArrayHasKey('CampaignNegativeKeywords', $response);
    }
}
?>