<?php
namespace Drupal\my_crud\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

class DeleteForm extends ConfirmFormBase{

    public $cid;

    public function getFormId(){
        return 'delete_form';
    }

    public function getQuestion(){
        return t('Delete Record?');
    }

    public function getCancelUrl(){
        return new Url('my_crud.mycrud_controller_listing');
    }

    public function getDescription(){
        return t('Are you sure Do you want to delete the record?');
    }

    public function getConfirmText(){
        return t('Delete it');
    }

    public function getCancelText(){
        return t('Cancel');
    }

    public function buildForm(array $form, FormStateInterface $form_state, $cid = null){
        $this->id = $cid;
        return parent::buildForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state){
        parent::validateForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state){
        $query = \Drupal::database();
        $query->delete('my_crud')->condition('id', $this->id)->execute();

        \Drupal::messenger()->addMessage('Successfully Deleted the Record');

        $form_state->setRedirect('my_crud.mycrud_controller_listing');
    }
}
?>