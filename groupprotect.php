<?php

require_once 'groupprotect.civix.php';
require_once 'CRM/Groupprotect/BAO/GroupProtect.php';

use CRM_Groupprotect_ExtensionUtil as E;


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

function groupprotect_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  CRM_Groupprotect_BAO_GroupProtect::validateForm($formName, $fields, $files, $form, $errors);
}
/**
 * Implements hook_civicrm_alterTemplateFile for specific extension processing
 *
 * @param $formName
 * @param $form
 * @param $context
 * @param $tplName
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterTemplateFile
 *
 */
function groupprotect_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName) {
  CRM_Groupprotect_BAO_GroupProtect::alterTemplateFile($formName, $form, $context, $tplName);
}

/**
 * Implements hook_civicrm_searchTasks for specific extension processing
 *
 * @param $objectName
 * @param $tasks
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_searchTasks*
 */
function groupprotect_civicrm_searchTasks($objectName, &$tasks) {
  CRM_Groupprotect_BAO_GroupProtect::searchTasks($objectName, $tasks);
}
/**
 * Implements hook_civicrm_apiWrappers
 *
 * @param $wrappers
 * @param $apiRequest
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_apiWrappers
 */
function groupprotect_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  // todo complete wrapper and implement
  if ($apiRequest['entity'] == 'GroupContact') {
    //$wrappers[] = new CRM_Groupprotect_GroupContactApiWrapper();
  }
}

function groupprotect_civicrm_pageRun( &$page ) {
  CRM_Groupprotect_BAO_GroupProtect::pageRun($page);
}
/**
 * Implements hook_civicrm_permission
 *
 * @param $permissions
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_permission
 */
function groupprotect_civicrm_permission(&$permissions) {
  $prefix = E::ts('CiviCRM Group Protect') . ': ';
  $permissions['manage protected groups'] = [
    'label' => $prefix . E::ts('manage protected groups'),
  ];
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

function _groupprotect_is_civirules_installed() {
  $installed = false;
  try {
    $extensions = civicrm_api3('Extension', 'get');
    foreach($extensions['values'] as $ext) {
      if ($ext['key'] == 'org.civicoop.civirules' && $ext['status'] == 'installed') {
        $installed = true;
      }
    }
    return $installed;
  } catch (Exception $e) {
    return false;
  }
  return false;
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
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function groupprotect_civicrm_enable() {
  _groupprotect_civix_civicrm_enable();
}
