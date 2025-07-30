<?php

use CRM_Groupprotect_ExtensionUtil as E;
use Civi\Test\CiviEnvBuilder;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * FIXME - Add test description.
 *
 * Tips:
 *  - With HookInterface, you may implement CiviCRM hooks directly in the test class.
 *    Simply create corresponding functions (e.g. "hook_civicrm_post(...)" or similar).
 *  - With TransactionalInterface, any data changes made by setUp() or test****() functions will
 *    rollback automatically -- as long as you don't manipulate schema or truncate tables.
 *    If this test needs to manipulate schema or truncate tables, then either:
 *       a. Do all that using setupHeadless() and Civi\Test.
 *       b. Disable TransactionalInterface, and handle all setup/teardown yourself.
 *
 * @group headless
 */
class CRM_Groupprotect_ApiTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {
    use Civi\Test\Api3TestTrait;
    use Civi\Test\Api4TestTrait;

  /**
   * Setup used when HeadlessInterface is implemented.
   *
   * Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
   *
   * @link https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
   *
   * @return \Civi\Test\CiviEnvBuilder
   *
   * @throws \CRM_Extension_Exception_ParseException
   */
  public function setUpHeadless(): CiviEnvBuilder {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  public function setUp(): void {
    parent::setUp();
  }

  public function tearDown(): void {
    parent::tearDown();
  }

  /**
   * Example: Test that a version is returned.
   * @dataProvider versionThreeAndFour
   */
  public function testGetProtectApiQuery($apiversion): void {
    $this->_apiversion = $apiversion;
    $groupID = $this->createTestRecord('Group', [
      'title' => 'Test Protected Group',
    ])['id'];
    $apiResult = $this->callAPISuccess('Group', 'Getprotect', ['group_id' => $groupID]);
    $key = ($apiversion === 3 ? $apiResult['id'] : 0);
    $this->assertEquals(0, $apiResult['values'][$key]['protected']);
    $config = CRM_Groupprotect_Config::singleton();
    civicrm_api3('Group', 'create', [
      'id' => $groupID,
      'custom_' . $config->getGroupProtectCustomField('id') => 1,
    ]);
    $apiResult = $this->callAPISuccess('Group', 'Getprotect', ['group_id' => $groupID]);
    $this->assertEquals(1, $apiResult['values'][$key]['protected']);
  }


}
