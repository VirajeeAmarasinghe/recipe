<?php
namespace Drupal\user_crud\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Determines access for user_crud add page
 */

 class UserCrudAddEditFormAccessCheck implements AccessInterface{
     public function access(AccountInterface $account){
        return AccessResult::allowedIfHasPermission($account, "create update user_crud content");
     }
 }

?>