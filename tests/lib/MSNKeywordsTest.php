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

class MSNKeywordsTest extends PHPUnit_Framework_TestCase {

    private $_obj = NULL;

    public function setUp() {
        MSNAdCenter::setUp();
        MSNKeywords::setResponseDefault(MSNAdCenter::RESPONSE_ARRAY);
    }

    public function tearDown() {
    }

    public function testKeywordd() {


        $newKeyword = array(
                'Text' => 'mittens',
                'BroadMatchBid' => array('Amount' => 0.5),
                'ContentMatchBid' => array('Amount' => 0.5),
                'ExactMatchBid' => array('Amount' => 0.5),
                'NegativeKeywords' => NULL
        );

        $response = MSNKeywords::add(567, array($newKeyword));
        $this->assertArrayHasKey('KeywordIds', $response);
    }

    public function testDelete() {

        $this->assertTrue(MSNKeywords::delete(567, array(10)));
    }

    public function testUpdate() {

        $this->assertTrue(MSNKeywords::update(567, array(10)));
    }

    public function testPause() {

        $this->assertTrue(MSNKeywords::pause(567, array(10)));
    }

    public function testResume() {

        $this->assertTrue(MSNKeywords::resume(567, array(10)));
    }

    public function testGetByIds() {

        $response = MSNKeywords::getByIds(567, array(10, 20, 30));
        $this->assertArrayHasKey('Keywords', $response);
    }

    // -------------------------------------------------------------------------

    // -- ADGROUPS

    public function testGetByAdGroupID() {

        $response = MSNKeywords::getByAdGroupId(123);
        $this->assertArrayHasKey('Keywords', $response);
    }

    public function testGetNegativeByAdGroupId() {

        $response = MSNKeywords::getNegativeByAdGroupId(56789, array(10, 20, 30));
        $this->assertArrayHasKey('AdGroupNegativeKeywords', $response);
    }

    public function testSetNegativeToAdGroups() {

        $response = MSNKeywords::setNegativeToAdGroups(56789, array(10 => array('bad1', 'bad2')));
        $this->assertTrue($response);
    }

    // -- CAMPAIGNS

    public function testSetNegativeToCampaigns() {

        $response = MSNKeywords::setNegativeToCampaigns(987546, array(6667 => array('bad1', 'bad2')));
        $this->assertTrue($response);
    }

    public function testGetNegativeByCampaignIds() {

        $response = MSNKeywords::getNegativeByCampaignIds(987546, array(1, 2, 3, 4));
        $this->assertArrayHasKey('CampaignNegativeKeywords', $response);
    }
}
?>