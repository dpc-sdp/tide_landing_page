<?php

namespace Drupal\tide_landing_page\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;

/**
 * Determines access for block layout based on roles.
 */
class BlockAccessCheck implements AccessInterface {

  public function access(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'administer content types');
  }

}
