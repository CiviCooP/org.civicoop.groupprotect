<?php

/**
 * Class for Group Protect specific processing
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 8 Dec 2015
 * @license AGPL-3.0
 */
class CRM_Groupprotect_BAO_GroupProtect {

  // TODO: if group is_reserved -> set protect = yes

  /**
   * Method to process civicrm buildForm hook
   *
   * User can only manage group settings for non-protected groups or if user has permissions
   */
  public static function buildForm($formName, $form) {
    if ($formName == 'CRM_Group_Form_Edit') {
      $protectGroupAllowed = CRM_Core_Permission::check('manage protected groups');
      if (!$protectGroupAllowed) {
        CRM_Core_Region::instance('page-body')->add(array('template' => 'GroupProtect.tpl'));
      }
    }
  }

  /**
   * Method to process civicrm pre hook:
   * If objectName = GroupContact and Group is a protected group, check if user has permission.
   * When user does not have permission, redirect to user context with status message
   *
   */
  public static function pre($op, $objectName, $objectId, $params) {
    if ($objectName == 'GroupContact' && self::groupIsProtected($objectId) == TRUE) {
      if (!CRM_Core_Permission::check('manage protected groups')) {
        CRM_Core_Session::setStatus(ts("You are not allowed to add or remove contacts to this group"), ts("Not allowed"), "error");
        $session = CRM_Core_Session::singleton();
        CRM_Utils_System::redirect($session->readUserContext());
      }
    }
  }

  /**
   * Method to check if group is protected with custom field
   *
   * @param int $groupId
   * @return boolean
   * @throws Exception when API errors retrieving custom group and field
   */
  private static function groupIsProtected($groupId) {
    $config = CRM_Groupprotect_Config::singleton();
    $query = "SELECT ".$config->getGroupProtectCustomField('column_name')." FROM ".
      $config->getGroupProtectCustomGroup('table_name')." WHERE entity_id = %1";
    return CRM_Core_DAO::singleValueQuery($query, array(1 => array($groupId, 'Integer')));
  }
}