<?php
namespace Drupal\user_crud\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'UserCrud' block
 * 
 * @Block(
 *  id = "user_crud_block",
 *  admin_label = @Translation("User Crud Block")
 * )
 */
class UserCrud extends BlockBase{
    /**
     * {@inheritDoc}
     */
    public function build(){
        $form = \Drupal::formBuilder()->getForm('Drupal\user_crud\Form\UserAddEditForm');

        return $form;
        
    }
}
?>