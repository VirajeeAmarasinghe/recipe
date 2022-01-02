<?php
namespace Drupal\entity_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EntityAddEditForm extends FormBase{
    /**
     * This needs to return a string that is the unique ID of your form. 
     * Namespace the form ID based on your module's name.
     * {@inheritDoc}
     */
    public function getFormId(){
        return 'entity_crud_add_edit_form';
    }

    /**
     * This returns a Form API array that defines each of the elements of your form.
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return array 
     */
    public function buildForm(array $form, FormStateInterface $form_state){       

        $data = array();

        if(isset($_GET['id'])){
           $data = entity_load('entity_crud', [$_GET['id']]);
        }

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#required' => TRUE,
            '#size' => 60,
            '#default_value' => (isset($data->name)) ? $data->name : '',
            '#maxlength' => 128,
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
        ];

        $form['description'] = [
            '#type' => 'textfield',
            '#title' => $this->t('description'),
            '#required' => TRUE,
            '#size' => 60,
            '#default_value' => (isset($data->description)) ? $data->description : '',
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
     * used to validate the data that's being collected.
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function validateForm(array &$form, FormStateInterface $form_state){
        if(is_numeric($form_state->getValue('name'))){
            $form_state->setErrorByName('name', $this->t('Error, The Name Must Be A String'));
        }
    }

    /**
     * collect data and save the data to the database.
     * 
     * {@inheritDoc}
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function submitForm(array &$form, FormStateInterface $form_state){
       
        $name = $form_state->getValue('name');
        $description = $form_state->getValue('description'); 
           
        if(isset($_GET['id'])){
            $entity_crud = entity_load('entity_crud', [$_GET['id']]);

            //update
            $entity_crud->name = $name;
            $entity_crud->description = $description;
            $entity_crud->save();
        }else{
            //insert data to database
            $entity_crud = entity_create('entity_crud', array('id' => $_GET['id']));
            $entity_crud->name = $name;
            $entity_crud->description = $description;
            $entity_crud->save();
        }
        
        //show message and redirect to list page
        \Drupal::messenger()->addStatus('Successfully saved');
        $url =new Url('entity_crud.display_data');
        $response = new RedirectResponse($url->toString());
        $response->send();
    }


}
?>