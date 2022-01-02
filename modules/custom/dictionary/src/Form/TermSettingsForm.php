<?php
/**
 * @file  
 * Contains \Drupal\dictionary\Form\TermSettingsForm
 */

 namespace Drupal\dictionary\Form;

 use Drupal\Core\Form\FormBase;
 use Drupal\Core\Form\FormStateInterface;

 /**
  * Class ContentEntityExampleSettingsForm
  * @package Drupal\dictionary\Form
  * @ingroup dictionary
  */

  class TermSettingsForm extends FormBase {
      /**
       * Returns an unique string identifying the form.
       * @return string 
       * 
       */
      public function getFormId(){
          return 'dictionary_term_settings';
      }

      /**
       * {@inheritDoc}
       *
       * @param array $form
       * @param FormStateInterface $form_state
       * @return void
       */
      public function submitForm(array &$form, FormStateInterface $form_state){
        //Empty implementation of the abstaract submit class.
      }

      /**
       * {@inheritDoc}
       */
      public function buildForm(array $form, FormStateInterface $form_state){
        $form['dictionary_term_settings']['#markup'] = 'Settings form for Dictionary Term. Manage field settings here.';
        return $form;
      }
  }
?>