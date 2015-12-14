<?php

require_once 'groupprotect.civix.php';

/**
 * Implements hook_civicrm_pre for specific extension processing
 *
 * @param $op
 * @param $objectName
 * @param $objectId
 * @param $params
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_pre
 */
function groupprotect_civicrm_pre($op, $objectName, $objectId, $params) {
  CRM_Groupprotect_BAO_GroupProtect::pre($op, $objectName, $objectId, $params);
}

/**
 * Implements hook_civicrm_buildForm for specific extension processing
 *
 * @param $formName
 * @param $form
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function groupprotect_civicrm_buildForm($formName, &$form) {
  CRM_Groupprotect_BAO_GroupProtect::buildForm($formName, $form);
}

/**
 * Implements hook_civicrm_permission
 *
 * @param $permissions
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_permission
 */
function groupprotect_civicrm_permission(&$permissions) {
  $prefix = ts('CiviCRM Group Protect') . ': ';
  $permissions['manage protected groups'] = $prefix . ts('manage protected groups');
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function groupprotect_civicrm_config(&$config) {

  // get extension config so custom group/field are created if not exist yet
  require_once 'CRM/Groupprotect/Config.php';
  CRM_Groupprotect_Config::singleton();

  _groupprotect_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 */
function groupprotect_civicrm_xmlMenu(&$files) {
  _groupprotect_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function groupprotect_civicrm_install() {
  _groupprotect_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function groupprotect_civicrm_uninstall() {
  _groupprotect_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function groupprotect_civicrm_enable() {
  _groupprotect_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function groupprotect_civicrm_disable() {
  _groupprotect_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function groupprotect_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _groupprotect_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function groupprotect_civicrm_managed(&$entities) {
  _groupprotect_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function groupprotect_civicrm_caseTypes(&$caseTypes) {
  _groupprotect_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function groupprotect_civicrm_angularModules(&$angularModules) {
_groupprotect_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function groupprotect_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _groupprotect_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
