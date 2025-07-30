<?php

namespace Civi\Api4\Action\Group;

use Civi\Api4\Generic\Result;
use Civi\Api4\Generic\AbstractAction;

/**
 * Get Group Protected Status
 */
class Getprotect extends AbstractAction {

  /**
   * Group ID
   * @var int
   * @required
   */
  protected $group_id;

  public function _run(Result $result) {
    $results = [];
    $isProtected = \CRM_Groupprotect_BAO_GroupProtect::groupIsProtected($this->group_id);
    $results[] = [
      'group_id' => $this->group_id,
      'protected' => $isProtected,
    ];
    $result->exchangeArray($results);
  }

}
