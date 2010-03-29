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

    private $_accountId = 987;

    public function setUp() {
        MSNAdCenter::setUp();
        MSNCampaigns::setResponseDefault(MSNAdCenter::RESPONSE_ARRAY);
    }

    public function tearDown() {

    }

    public function testAdd() {

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

        $response = MSNCampaigns::add(987, array($newCampaign));
        $this->assertArrayHasKey('CampaignIds', $response);
    }

    public function testDelete() {
        $this->assertTrue(MSNCampaigns::delete($this->_accountId, array(10)));
    }

    public function testUpdate() {
        $this->assertTrue(MSNCampaigns::update($this->_accountId, array(10)));
    }

    public function testPause() {
        $this->assertTrue(MSNCampaigns::pause($this->_accountId, array(10)));
    }

    public function testResume() {
        $this->assertTrue(MSNCampaigns::resume($this->_accountId, array(10)));
    }

    public function testGetByIds() {
        $response = MSNCampaigns::getByIds($this->_accountId, array(10, 20, 30));
        $this->assertArrayHasKey('Campaigns', $response);
    }

    // -------------------------------------------------------------------------

    public function testGetByAccountId() {
        $response = MSNCampaigns::getByAccountId($this->_accountId);
        $this->assertArrayHasKey('Campaigns', $response);
    }

    public function testGetInfoByAccountId() {
        $response = MSNCampaigns::getInfoByAccountId($this->_accountId);
        $this->assertArrayHasKey('CampaignsInfo', $response);
    }

    // -- KEYWORDS

    public function testSetNegativeKeywords() {
        $response = MSNCampaigns::setNegativeKeywords($this->_accountId,
                array(10 => array('bad1', 'bad2')));
        $this->assertTrue($response);
    }

    public function testGetNegativeKeywords() {
        $response = MSNCampaigns::getNegativeKeywords($this->_accountId, array(10, 20, 30));
        $this->assertArrayHasKey('CampaignNegativeKeywords', $response);
    }

    public function testGetTargets() {
        $response = MSNCampaigns::getTargets(array(10, 20, 30));
        $this->assertArrayHasKey('Targets', $response);
    }

    public function testGetAdGroups() {
        $response = MSNCampaigns::getAdGroups(10);
        $this->assertArrayHasKey('AdGroups', $response);
    }

    public function testGetAdGroupsInfo() {
        $response = MSNCampaigns::getAdGroupsInfo(10);
        $this->assertArrayHasKey('AdGroupsInfo', $response);
    }
}
?>