<?php
namespace Drupal\create_vocabulary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\taxonomy\Entity\Vocabulary;

class VocabularyAddEditForm extends FormBase{

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getFormId(){
        return 'create_vocabulary_add_edit_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state){
        $vocabulary = null;

        if(isset($_GET['id'])){
            $vocabulary = Vocabulary::load($_GET['id']);
        }

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Vocabulary Name'),
            '#required' => TRUE,
            '#size' => 60,
            '#default_value' => (!is_null($vocabulary)) ? $vocabulary->get('name') : '',
            '#maxlength' => 128,
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
        ];

        $form['description'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Description'),
            '#required' => TRUE,
            '#size' => 80,
            '#default_value' => (!is_null($vocabulary)) ? $vocabulary->get('description') : '',
            '#maxlength' => 300,
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('save'),
            '#button_type' => 'primary'
        ];

        return $form;
    }

    /**
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function validateForm(array &$form, FormStateInterface $form_state){
        if($form_state->getValue('name')===""){
            $form_state->setErrorByName('name', $this->t('Error, Enter Name.'));
        }

        if($form_state->getValue('description')===""){
            $form_state->setErrorByName('description', $this->t('Error, Enter Description.'));
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function submitForm(array &$form, FormStateInterface $form_state){
        $name = $form_state->getValue('name');
        $description = $form_state->getValue('description');

        $vocabulary = null;
        if(isset($_GET['id'])){
            $vocabulary = Vocabulary::load($_GET['id']);  
            $vocabulary->set('name', $name);
            $vocabulary->set('description', $description);
                      
        }else{
            $vocabulary = Vocabulary::create(array(
                'vid' => strtolower(str_replace(" ","_", $name)),
                'name' => $name,  
                'description' => $description                
            ));
          
        }

        $vocabulary->save();

        \Drupal::messenger()->addStatus('Successfully saved');
        $url =new Url('create_vocabulary.display_vocabularies');
        $response = new RedirectResponse($url->toString());
        $response->send();
    }
}
?>