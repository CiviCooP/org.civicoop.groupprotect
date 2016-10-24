<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

if (_groupprotect_is_civirules_installed()) {
  return array (
    0 =>
      array (
        'name' => 'Civirules:Action.AddToProtectedGroup',
        'entity' => 'CiviRuleAction',
        'params' =>
          array (
            'version' => 3,
            'name' => 'AddToProtectedGroup',
            'label' => 'Add to protected group',
            'class_name' => 'CRM_Groupprotect_CiviRulesActions_Add',
            'is_active' => 1
          ),
      ),
    1 =>
      array (
        'name' => 'Civirules:Action.RemoveFromProtectedGroup',
        'entity' => 'CiviRuleAction',
        'params' =>
          array (
            'version' => 3,
            'name' => 'RemoveFromProtectedGroup',
            'label' => 'Remove from protected group',
            'class_name' => 'CRM_Groupprotect_CiviRulesActions_Remove',
            'is_active' => 1
          ),
      ),
  );
}