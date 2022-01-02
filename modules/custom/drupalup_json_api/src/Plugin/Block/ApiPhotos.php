<?php
namespace Drupal\drupalup_json_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Block of Photos
 *
 * @Block(
 *   id = "photos_api_block",
 *   admin_label = @Translation("Photos API Custom Block")
 * )
 */
class ApiPhotos extends BlockBase{

    /**
   * This method will render a render-able array. 
   * The complex contents like forms and views can also be returned.
   * {@inheritdoc}
   */
    public function build() {
        return [
            '#theme' => 'photos_theme'                   
        ];
     }  
}
?>