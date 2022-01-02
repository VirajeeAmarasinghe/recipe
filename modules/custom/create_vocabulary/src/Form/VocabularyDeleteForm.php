<?php
namespace Drupal\create_vocabulary\Form;

use \Drupal\taxonomy\Entity\Vocabulary;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

/**
 * Class VocabularyDeleteForm
 * @package Drupal\create_vocabulary\Form
 */
class VocabularyDeleteForm extends ConfirmFormBase{
    public $id;
    
    public function getFormId(){
        return 'create_vocabulary_delete_form';
    }

    public function getQuestion(){
        return t('Delete vocabulary?');
    }

    public function getCancelUrl(){
        return new Url('create_vocabulary.display_vocabularies');
    }

    public function getDescription(){
        return t('Do you want to delete the vocabulary %id ?', array('%id' => $this->id));
    }

    public function getConfirmText(){
        return t('Delete');
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
        $vocabulary = Vocabulary::load($this->id); 
        $vocabulary->delete();

        \Drupal::messenger()->addStatus('Successfully Deleted.');

        $form_state->setRedirect('create_vocabulary.display_vocabularies');
    }
}

?>