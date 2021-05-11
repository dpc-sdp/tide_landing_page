<?php

namespace Drupal\tide_landing_page\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;

/**
 * Determines access for block layout based on roles.
 */
class BlockAccessCheck implements AccessInterface {

  /**
   * Checks access.
   *
   * @param Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   *
   * @return bool
   *   A \Drupal\Core\Access\AccessInterface constant value.
   */
  public function access(AccountInterface $account) {
    $rolesWithAccessRestricted = [
      'editor',
      'approver',
      'site_admin',
    ];

    $roleFound = FALSE;

    $roles = $account->getRoles();
    if (!empty($roles)) {
      foreach ($roles as $role) {
        if (in_array($role, $rolesWithAccessRestricted)) {
          $roleFound = TRUE;
          break;
        }
      }
    }

    if ($roleFound) {
      return AccessResult::forbidden();
    }

    return AccessResult::neutral();
  }

}
