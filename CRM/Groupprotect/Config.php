<?php

/**
 * Class following Singleton pattern for specific extension configuration
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 8 Dec 2015
 * @license AGPL-3.0
 */
class CRM_Groupprotect_Config {

  // singleton pattern
  static private $_singleton = NULL;

  // property holds the custom group for protected groups with custom fields in array ['custom_fields']
  protected $_groupProtectCustomGroup = array();

  /**
   * CRM_Groupprotect_Config constructor.
   */
  function __construct() {
    $this->setGroupProtectCustomGroup();
  }

  /**
   * Getter for custom group
   *
   * @return array
   */
  public function getGroupProtectCustomGroup($key = NULL) {
    if ($key) {
      return $this->_groupProtectCustomGroup[$key];
    } else {
      return $this->_groupProtectCustomGroup;
    }
  }
  /**
   * Getter for group protect custom field
   *
   * @return array
   */
  public function getGroupProtectCustomField($key = NULL) {
    foreach ($this->_groupProtectCustomGroup['custom_fields'] as $customFieldId => $customField) {
      if ($customField['name'] == 'group_protect') {
        if ($key) {
          return $customField[$key];
        } else {
          return $customField;
        }
      }
    }
  }

  /**
   * Gets or creates custom groups (with custom fields) for group_protect
   *
   * @throws API_Exception when unable to find or create custom group
   */
  private function setGroupProtectCustomGroup() {
    try {
      $customGroup = civicrm_api3('CustomGroup', 'Getsingle', array('name' => 'group_protect'));
    } catch (CiviCRM_API3_Exception $ex) {
      $customGroupParams = array(
        'name' => 'group_protect',
        'extends' => 'Group',
        'title' => 'Protect Group',
        'table_name' => 'civicrm_value_group_protect',
        'is_active' => 1,
        'is_reserved' => 1);
      try {
        $createdGroup = civicrm_api3('CustomGroup', 'Create', $customGroupParams);
        foreach ($createdGroup['values'] as $createdGroupId => $createdGroup) {
          $customGroup = $createdGroup;
        }
      } catch (CiviCRM_API3_Exception $ex) {
        throw new API_Exception('Could not create custom group "group_protect" nor find an existing one.
          Error from API CustomGroup Create: '.$ex->getMessage(), 9010);
      }
    }
    $customGroup['custom_fields'] = $this->setGroupProtectCustomField($customGroup['id']);
    $this->_groupProtectCustomGroup = $customGroup;
  }

  /**
   * Method to create custom field for group protect
   * @param $customGroupId
   * @return array
   * @throws API_Exception
   */
  private function setGroupProtectCustomField($customGroupId) {
    $customField = array();
    if (!empty($customGroupId)) {
      $findFieldParams = array(
        'name' => 'group_protect',
        'custom_group_id' => $customGroupId);
      try {
        $retrievedField = civicrm_api3('CustomField', 'Getsingle', $findFieldParams);
        $customField[$retrievedField['id']] = $retrievedField;
      } catch (CiviCRM_API3_Exception $ex) {
        $createFieldParams = array(
          'custom_group_id' => $customGroupId,
          'name' => 'group_protect',
          'label' => 'Protect Group?',
          'column_name' => 'group_protect',
          'data_type' => 'Boolean',
          'html_type' => 'Radio',
          'is_searchable' => 1,
          'is_active' => 1);
        try {
          $createdField = civicrm_api3('CustomField', 'Create', $createFieldParams);
          foreach ($createdField['values'] as $createdFieldId => $createdField) {
            $customField[$createdFieldId] = $createdField;
          }
        } catch (CiviCRM_API3_Exception $ex) {
          throw new API_Exception('Could not create custom field "group_protect" nor find an existing one.
          Error from API CustomField Create: '.$ex->getMessage(), 9011);
        }
      }
    }
    return $customField;
  }
  /**
   * Function to return singleton object
   *
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Groupprotect_Config();
    }
    return self::$_singleton;
  }
}