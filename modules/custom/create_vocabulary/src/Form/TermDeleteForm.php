<?php
namespace Drupal\create_vocabulary\Form;

use \Drupal\taxonomy\Entity\Term;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

/**
 * Class TermDeleteForm
 * @package Drupal\create_vocabulary\Form
 */
class TermDeleteForm extends ConfirmFormBase{
    public $tid;
    public $vid;
    
    public function getFormId(){
        return 'create_vocabulary_delete_form';
    }

    public function getQuestion(){
        return t('Delete term?');
    }

    public function getCancelUrl(){        
        return new Url('create_vocabulary.show_vocabulary', ['id' => $this->vid]);
    }

    public function getDescription(){
        return t('Do you want to delete term %id ?', array('%id' => $this->tid));
    }

    public function getConfirmText(){
        return t('Delete it!');
    }

    public function getCancelText(){
        return t('Cancel');
    }

    
    public function buildForm(array $form, FormStateInterface $form_state, $vocabulary_id=null, $id=null){
        $this->tid = $id; 
        $this->vid=$vocabulary_id;
        return parent::buildForm($form, $form_state);
    }

    
    public function validateForm(array &$form, FormStateInterface $form_state){
        parent::validateForm($form, $form_state);
    }
    
    
    public function submitForm(array &$form, FormStateInterface $form_state){
        $term = Term::load($this->tid); 
        $term->delete();

        \Drupal::messenger()->addStatus('Successfully Deleted.');

        $form_state->setRedirect('create_vocabulary.show_vocabulary', ['id' => $this->vid]);
    }
}

?>