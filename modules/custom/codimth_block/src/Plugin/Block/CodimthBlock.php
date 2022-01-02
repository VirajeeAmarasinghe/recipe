<?php
namespace Drupal\codimth_block\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'Codimth Block' Block.
 * 
 * @Block(
 *   id = "codimth_block",
 *   admin_label = @Translation("Codimth Block"),
 *   category = @Translation("Codimth")
 * )
 */
class CodimthBlock extends BlockBase implements BlockPluginInterface {
    /**
     * required method which is expected to return a render array defining the content you want your block to display
     * {@inheritdoc}
     */
    public function build(){
        $config = $this->getConfiguration();
        $codimth_copyright = $config['codimth_copyright']; 

        $markup='<span>'.$codimth_copyright.'</span>';

        return [
            '#markup' => $markup
        ];
    }
    
    /**
     * This method allows you to define a block configuration form using the Form API.
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state){
        $form = parent::blockForm($form, $form_state);

        $config = $this->getConfiguration();

        $current_year = date("Y");

        $codimth_copyright = $this->t('©@year codimTh. All Rights Reserved.', [
            '@year' => $current_year
        ]);

        $form['codimth_copyright'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Copyright'),
            '#description' => $this->t('Write your copyright text.'),
            '#default_value' => isset($config['codimth_copyright']) ? $config['codimth_copyright'] : $codimth_copyright,
        ];

        return $form;
    }

    /**
     * This method used to save a configuration, defined on the blockForm() method.
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state){
        parent::blockSubmit($form, $form_state);
        
        $this->setConfigurationValue('codimth_copyright', $form_state->getValue('codimth_copyright'));
    }  
    
    /**
     * This method used to validate block configuration form
     * {@inheritdoc}
     */
    public function blockValidate($form, FormStateInterface $form_state){
        if(empty($form_state->getValue('codimth_copyright'))){
            $form_state->setErrorByName('codimth_copyright', t('This field is required'));
        }
    }

    /**
     * This method used if you want to change block cache max time
     * {@inheritdoc}
     * return 0 If you want to disable caching for this block.
     */
    public function getCacheMaxAge(){
        return 0;
    }

    /**
     * Defines a custom user access logic. It is expected to return an AccessResult object.
     * {@inheritdoc}
     */
    public function access(AccountInterface $account, $return_as_object = FALSE){
        return AccessResult::allowedIfHasPermission($account, 'access content');
    }
}
?>