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

class MSNAdGroupsTest extends PHPUnit_Framework_TestCase {

    private $_campaignId = 10;

    public function setUp() {
        MSNAdCenter::setUp();
        MSNAdGroups::setResponseDefault(MSNAdCenter::RESPONSE_ARRAY);
    }

    public function tearDown() {
    }

    public function testAdd() {

        $newAg = array();
        $newAg['Name'] = 'HeadWear';
        $newAg['AdDistribution'] = 'Search';
        $newAg['BiddingModel'] = 'Keyword';
        $newAg['PricingModel'] = 'Cpc';
        $newAg['ExactMatchBid'] = array('Amount' => 10);
        $newAg['StartDate'] = NULL;
        $newAg['EndDate'] = array
                (
                'Day'   => 31,
                'Month' => 12,
                'Year'  => 2011
        );
        $newAg['LanguageAndRegion'] = 'EnglishUnitedStates';
        $newAg['NegativeKeywords'] = NULL;

        $response = MSNAdGroups::add($this->_campaignId, array($newAg));
        $this->assertArrayHasKey('AdGroupIds', $response);
    }

    public function testDelete() {
        $this->assertTrue(MSNAdGroups::delete($this->_campaignId, array(10)));
    }

    public function testUpdate() {
        $this->assertTrue(MSNAdGroups::update($this->_campaignId, array(10)));
    }

    public function testPause() {
        $this->assertTrue(MSNAdGroups::pause($this->_campaignId, array(10)));
    }

    public function testResume() {
        $this->assertTrue(MSNAdGroups::resume($this->_campaignId, array(10)));
    }

    public function testGetByIds() {
        $response = MSNAdGroups::getByIds($this->_campaignId, array(10, 20, 30));
        $this->assertArrayHasKey('AdGroups', $response);
    }

    // -------------------------------------------------------------------------

    public function testSubmitForApproval() {
        $response = MSNAdGroups::submitForApproval(10);
        $this->assertTrue($response);
    }

    // -- CAMPAIGN

    public function testGetByCampaignId() {
        $response = MSNAdGroups::getByCampaignId($this->_campaignId);
        $this->assertArrayHasKey('AdGroups', $response);
    }

    public function testGetInfoByCampaignId() {
        $response = MSNAdGroups::getInfoByCampaignId($this->_campaignId);
        $this->assertArrayHasKey('AdGroupsInfo', $response);
    }

    // -- ADS

    public function testGetAds() {
        $response = MSNAdGroups::getAds(10);
        $this->assertArrayHasKey('Ads', $response);
    }

    // -- SITEPLACEMENTS

    public function testGetSitePlacements() {
        $response = MSNAdGroups::getSitePlacements(10);
        $this->assertArrayHasKey('SitePlacements', $response);
    }

    // -- TARGETS

    public function testSetTargetTo() {
        $response = MSNAdGroups::setTargetTo(10, 987564321);
        $this->assertTrue($response);
    }

    public function testGetTargets() {
        $response = MSNAdGroups::getTargets(array(10, 20, 30));
        $this->assertArrayHasKey('Targets', $response);
    }

    public function testDeleteTarget() {
        $response = MSNAdGroups::deleteTarget(10);
        $this->assertTrue($response);
    }

    // -- KEYWORDS

    public function testGetKeywords() {
        $response = MSNAdGroups::getKeywords(10);
        $this->assertArrayHasKey('Keywords', $response);
    }

    public function testGetNegativeKeywords() {
        $response = MSNAdGroups::getNegativeKeywords($this->_campaignId, array(10));
        $this->assertArrayHasKey('AdGroupNegativeKeywords', $response);
    }

    public function testSetNegativeKeywords() {
        $response = MSNAdGroups::setNegativeKeywords($this->_campaignId, array(10 => array('bad1', 'bad2', 'bad3')));
        $this->assertTrue($response);
    }

    // -- BEHAVIOURAL BIDS

    public function testGetBehavioralBids() {
        $response = MSNAdGroups::getBehavioralBids(10);
        var_dump($response);
        $this->assertArrayHasKey('BehavioralBids', $response);
    }
}
?>