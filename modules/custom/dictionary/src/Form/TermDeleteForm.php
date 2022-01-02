<?php
/**
 * @file
 * Contains \Drupal\dictionary\Form\TermDeleteForm
 */
namespace Drupal\dictionary\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a content_entity_example entity
 * @ingroup dictionary
 */

 class TermDeleteForm extends ContentEntityConfirmFormBase{

    /**
     * {@inheritDoc}
     */
     public function getQuestion(){
         return $this->t('Are you sure you want to delete entity %name?', array('%name' => $this->entity->label()));
     }

     /**
      * {@inheritDoc}
      *
      * If the delete command is canceled, return to the contact list
      */
     public function getCancelUrl(){
         return new Url('entity.dictionary_term.collection');
     }

     /**
      * {@inheritDoc}      *
      * 
      */
     public function getConfirmText(){
         return $this->t('Delete');
     }


     /**
      * {@inheritDoc}
      *
      * Delete the entity and log the event. logger() replaces the watchdog
      */
     public function submitForm(array &$form, FormStateInterface $form_state){
        $entity = $this->getEntity();
        $entity->delete();

        $this->logger('dictionary')->notice('deleted %title.', array(
            '%title' => $this->entity->label()
        ));

        $form_state->setRedirect('entity.dictionary_term.collection');
     }

 }
?>