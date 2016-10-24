<?php

/**
 * Class for Group Protect specific processing
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 8 Dec 2015
 * @license AGPL-3.0
 */
class CRM_Groupprotect_BAO_GroupProtect {
  /**
   * Method to process civicrm pageRun hook
   *
   * action items only allowed for unprotected groups or if user has permission
   * // todo now fixed in copy of template in extension, fix with core fix using jQuery
   *
   * @param object page
   */
  public static function pageRun(&$page) {
    $pageName = $page->getVar('_name');
    if ($pageName == 'CRM_Contact_Page_View_GroupContact') {
      $page->assign('userHasProtectGroup', self::userProtectPermitted());
    }
  }

  /**
   * Method to process civicrm buildForm hook
   *
   * User can only manage group settings for non-protected groups or if user has permissions
   *
   * @param $formName
   * @param $form
   * @access public
   * @static
   */
  public static function buildForm($formName, &$form) {
    // todo make sure groups are NOT removed in smog contact? if possible....
    if (self::userProtectPermitted() == FALSE) {
      switch ($formName) {
        case 'CRM_Group_Form_Edit':
          // todo only non-ACL groups
          CRM_Core_Region::instance('page-body')->add(array('template' => 'GroupProtect.tpl'));
          break;
        case 'CRM_Contact_Form_Task_AddToGroup':
          self::removeProtectedGroups($form);
          break;
        case 'CRM_Contact_Form_Task_RemoveFromGroup':
          self::removeProtectedGroups($form);
          break;
        case 'CRM_Contact_Form_GroupContact':
          break;
      }
    }
  }

  /**
   * Method to remove protected groups from option lists
   *
   * @param $form
   * @access private
   * @static
   */
  private static function removeProtectedGroups(&$form) {
    $groupElement = $form->getElement('group_id');
    $options = &$groupElement->_options;
    foreach ($options as $optionId => $optionValues) {
      if (!empty($optionValues['attr']['value'])) {
        if (self::groupIsProtected($optionValues['attr']['value']) == TRUE) {
          unset($options[$optionId]);
        }
      }
    }
  }
  /**
   * Method to remove 'add to group' action from task list of search result if group is protected and user does
   * not have permission
   *
   * @param string $objectName
   * @param array $tasks
   * @access public
   * @static
   */
  public static function searchTasks($objectName, &$tasks) {
    $objectName = strtolower($objectName);
    // when contact is pressed on manage group form and user does not have permissions
    if ($objectName == 'contact' && self::userProtectPermitted() == FALSE) {
      $requestValues = CRM_Utils_Request::exportValues();
      if (self::relevantSearchTasks($requestValues) == TRUE) {
        if (self::groupIsProtected($requestValues['gid'])) {
          self::removeActionsFromTasks($tasks);
        }
      }
    }
  }

  /**
   * Method to remove actions from task list when required
   *
   * @param $tasks
   */
  private static function removeActionsFromTasks(&$tasks) {
    foreach ($tasks as $taskId => $taskValues) {
      if (isset($taskValues['class'])) {
        if ($taskValues['class'] == 'CRM_Contact_Form_Task_AddToGroup' || $taskValues['class'] == 'CRM_Contact_Form_Task_RemoveFromGroup') {
          unset($tasks[$taskId]);
        }
      }
    }
  }

  /**
   * Method to find out if I have a relevant scenario where I need to check the tasks
   *
   * @param $requestValues
   * @return bool
   */
  private static function relevantSearchTasks($requestValues) {
    // check request to see if it is coming from Manage Groups/Contact
    if (isset($requestValues['q']) && $requestValues['q'] == 'civicrm/group/search') {
      if (isset($requestValues['context']) && $requestValues['context'] == 'smog') {
        if (isset($requestValues['gid']) && !empty($requestValues['gid'])) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * Method to check if group is protected with custom field
   *
   * @param int $groupId
   * @return boolean
   * @throws Exception when API errors retrieving custom group and field
   */
  public static function groupIsProtected($groupId) {
    $config = CRM_Groupprotect_Config::singleton();
    $query = "SELECT ".$config->getGroupProtectCustomField('column_name')." FROM ".
      $config->getGroupProtectCustomGroup('table_name')." WHERE entity_id = %1";
    return CRM_Core_DAO::singleValueQuery($query, array(1 => array($groupId, 'Integer')));
  }

  /**
   * Method to check if logged in user has permission to manage protected groups

   * @return bool
   */
  public static function userProtectPermitted() {
    return CRM_Core_Permission::check('manage protected groups');
  }
}