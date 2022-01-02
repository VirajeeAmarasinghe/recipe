<?php
namespace Drupal\entity_crud\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

/**
 * Class DeleteForm
 * @package Drupal\entity_crud\Form
 */
class DeleteForm extends ConfirmFormBase{
    public $id;
    
    public function getFormId(){
        return 'entity_crud_delete_form';
    }

    public function getQuestion(){
        return t('Delete data?');
    }

    public function getCancelUrl(){
        return new Url('entity_crud.display_data');
    }

    public function getDescription(){
        return t('Do you want to delete data number %id ?', array('%id' => $this->id));
    }

    public function getConfirmText(){
        return t('Delete it!');
    }

    public function getCancelText(){
        return t('Cancel');
    }

    
    public function buildForm(array $form, FormStateInterface $form_state, $id=null){
        $this->id = $id; 
        return parent::buildForm($form, $form_state);
    }

    
    public function validateForm(array &$form, FormStateInterface $form_state){
        parent::validateForm($form, $form_state);
    }
    
    
    public function submitForm(array &$form, FormStateInterface $form_state){
        //entity delete

        \Drupal::messenger()->addStatus('Successfully Deleted.');

        $form_state->setRedirect('user_crud.display_data');
    }
}

?>