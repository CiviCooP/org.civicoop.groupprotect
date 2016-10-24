<?php

/**
 * Group.Getprotect API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_group_Getprotect_spec(&$spec) {
  $spec['group_id']['api.required'] = 1;
}

/**
 * Group.Getprotect API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_group_Getprotect($params) {
  if (array_key_exists('group_id', $params)) {
    $isProtected = CRM_Groupprotect_BAO_GroupProtect::groupIsProtected($params['group_id']);
    $result[$params['group_id']] = array(
      'group_id' => $params['group_id'],
      'protected' => $isProtected
    );
    return civicrm_api3_create_success($result, $params, 'Group', 'getprotect');
  } else {
    throw new API_Exception('You need to pass parameter group_id to the Group getprotect AP',  1000);
  }
}

