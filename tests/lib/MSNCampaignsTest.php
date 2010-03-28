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

class MSNCampaignsTest extends PHPUnit_Framework_TestCase {

    private $_obj = NULL;

    private $_accountId = 987;

    public function setUp() {
        $this->_obj = new MSNCampaigns();
        $this->_obj->setResponseDefault(MSNAdCenter::RESPONSE_ARRAY);
    }

    public function tearDown() {

    }

    public function testAdd() {
        $campaign = $this->_obj;

        $newCampaign = array(
                'BudgetType' => 'MonthlyBudgetSpendUntilDepleted',
                'ConversionTrackingEnabled' => 'false',
                'DaylighSaving' => 'true',
                'Description' => 'Clothing products for winter',
                'MonthlyBudget' => 5000,
                'Name' => 'Winter Clothing',
                'NegativeKeywords' => null,
                'TimeZone' => 'PacificTimeUSCanadaTijuana',
        );

        $response = $campaign->add(987, array($newCampaign));
        $this->assertArrayHasKey('CampaignIds', $response);
    }

    public function testDelete() {
        $campaign = $this->_obj;
        $this->assertTrue($campaign->delete($this->_accountId, array(10)));
    }

    public function testUpdate() {
        $campaign = $this->_obj;
        $this->assertTrue($campaign->update($this->_accountId, array(10)));
    }

    public function testPause() {
        $campaign = $this->_obj;
        $this->assertTrue($campaign->pause($this->_accountId, array(10)));
    }

    public function testResume() {
        $campaign = $this->_obj;
        $this->assertTrue($campaign->resume($this->_accountId, array(10)));
    }

    public function testGetByIds() {
        $campaign = $this->_obj;
        $response = $campaign->getByIds($this->_accountId, array(10, 20, 30));
        $this->assertArrayHasKey('Campaigns', $response);
    }

    // -------------------------------------------------------------------------

    public function testGetByAccountId() {
        $campaign = $this->_obj;
        $response = $campaign->getByAccountId($this->_accountId);
        $this->assertArrayHasKey('Campaigns', $response);
    }

    public function testGetInfoByAccountId() {
        $campaign = $this->_obj;
        $response = $campaign->getInfoByAccountId($this->_accountId);
        $this->assertArrayHasKey('CampaignsInfo', $response);
    }

    // -- KEYWORDS

    public function testSetNegativeKeywords() {
        $campaign = $this->_obj;
        $response = $campaign->setNegativeKeywords($this->_accountId,
                array(10 => array('bad1', 'bad2')));
        $this->assertTrue($response);
    }

    public function testGetNegativeKeywords() {
        $campaign = $this->_obj;
        $response = $campaign->getNegativeKeywords($this->_accountId, array(10, 20, 30));
        $this->assertArrayHasKey('CampaignNegativeKeywords', $response);
    }

    public function testGetTargets() {
        $campaign = $this->_obj;
        $response = $campaign->getTargets(array(10, 20, 30));
        $this->assertArrayHasKey('Targets', $response);
    }

    public function testGetAdGroups() {
        $campaign = $this->_obj;
        $response = $campaign->getAdGroups(10);
        $this->assertArrayHasKey('AdGroups', $response);
    }

    public function testGetAdGroupsInfo() {
        $campaign = $this->_obj;
        $response = $campaign->getAdGroupsInfo(10);
        $this->assertArrayHasKey('AdGroupsInfo', $response);
    }
}
?>