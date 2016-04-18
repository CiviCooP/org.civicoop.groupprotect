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
  public static function pre($op, $objectName, $objectId, $params)
  {
    if ($objectName == 'GroupContact' && self::groupIsProtected($objectId) == TRUE) {
      // check if request is from webform, and allow groupcontact action if from webform
      $webFormRequest = FALSE;
      $request = CRM_Utils_Request::exportValues();
      if (isset($request['form_id'])) {
        $requestParts = explode('_', $request['form_id']);
        if (isset($requestParts[2])) {
          if ($requestParts[0] == 'webform' && $requestParts[1] == 'client' && $requestParts[2] = 'form') {
            $webFormRequest = TRUE;
          }
        }
      }
      if (!$webFormRequest) {
        if (!CRM_Core_Permission::check('manage protected groups')) {
          CRM_Core_Session::setStatus(ts("You are not allowed to add or remove contacts to this group"), ts("Not allowed"), "error");
          // if from FindExpert report, redirect to entryURL
          if (isset($request['_qf_default']) && $request['_qf_default'] == "FindExpert:submit") {
            CRM_Utils_System::redirect(CRM_Utils_System::url($request['q'], 'reset=1', true));
          } else {
            $session = CRM_Core_Session::singleton();
            CRM_Utils_System::redirect($session->readUserContext());
          }
        }
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