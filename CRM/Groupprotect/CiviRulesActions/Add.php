<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_Groupprotect_CiviRulesActions_Add extends CRM_CivirulesActions_GroupContact_GroupContact {

  /**
   * Method to set the api action
   *
   * @return string
   * @access protected
   */
  protected function getApiAction() {
    return 'create';
  }

  protected function alterApiParameters($params, CRM_Civirules_TriggerData_TriggerData $triggerData) {
    CRM_Groupprotect_BAO_GroupProtect::bypassPermissionCheck();
    return parent::alterApiParameters($params, $triggerData);
  }

}