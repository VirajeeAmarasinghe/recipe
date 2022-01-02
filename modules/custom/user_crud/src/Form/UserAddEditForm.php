<?php
namespace Drupal\user_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserAddEditForm extends FormBase{
    /**
     * This needs to return a string that is the unique ID of your form. 
     * Namespace the form ID based on your module's name.
     * {@inheritDoc}
     */
    public function getFormId(){
        return 'user_crud_add_edit_form';
    }

    /**
     * This returns a Form API array that defines each of the elements of your form.
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return array 
     */
    public function buildForm(array $form, FormStateInterface $form_state){
        $conn = Database::getConnection();

        $data = array();

        if(isset($_GET['id'])){
            $query = $conn->select('user_crud_table', 'm')
            ->condition('id', $_GET['id'])
            ->fields('m');

            $data = $query->execute()->fetchAssoc();
        }

        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('first name'),
            '#required' => TRUE,
            '#size' => 60,
            '#default_value' => (isset($data['first_name'])) ? $data['first_name'] : '',
            '#maxlength' => 128,
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('last name'),
            '#required' => TRUE,
            '#size' => 60,
            '#default_value' => (isset($data['last_name'])) ? $data['last_name'] : '',
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('email'),
            '#required' => TRUE,
            '#default_value' => (isset($data['email'])) ? $data['email'] : '',
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
        ];
        
        $form['picture'] = [
            '#type' => 'managed_file',
            '#title' => t('picture'),
            '#description' => $this->t('Choose Image gif png jpg jpeg'),
            '#required' => TRUE,
            '#default_value' => (isset($data['fid'])) ? [$data['fid']] : [],
            '#upload_location' => 'public://images/',
            '#upload_validators' => [
                'file_validate_extensions' => array('gif png jpg jpeg'),
                'file_validate_size' => array(2*1024*1024)
            ]
        ];

        $form['phone'] = [
            '#type' => 'tel',
            '#title' => $this->t('phone'),
            '#required' => TRUE,
            '#default_value' => (isset($data['phone'])) ? $data['phone'] : '',
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
        ];

        $form['dob'] = [
            '#title' => $this->t('Date of Birth'),
            '#type' => 'single_date_time',
            '#date_timezone' => date_default_timezone_get(),
            '#default_value' => NULL,
            '#date_type' =>  'date',
            '#required' => TRUE,
            '#default_value' => (isset($data['dob'])) ? $data['dob'] : '',
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
           ] + \Drupal\single_datetime\AttributeHelper::allElementAttributes();

           $options = array(
            'M' => t('Male'),
            'F' => t('Female')            
          );
          
          $form['gender'] = [
            '#type' => 'radios',
            '#title' => t('Gender'),
            '#options' => $options,
            '#required' => TRUE,
            '#default_value' => (isset($data['gender'])) ? $data['gender'] : 'M',
          ];

        $form['select'] = [
            '#type' => 'select',
            '#title' => $this->t('Select element'),
            '#options' => [
                '1' => $this->t('One'),
                '2' => [
                    '2.1' => $this->t('Two point one'),
                    '2.2' => $this->t('Two point two')
                ],
                '3' => $this->t('Three')
            ],
            '#required' => TRUE,
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12'],
            '#default_value' => (isset($data['select'])) ? $data['select'] : ''
        ];

        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('message'),
            '#required' => TRUE,
            '#default_value' => (isset($data['message'])) ? $data['message'] : '',
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
        if(is_numeric($form_state->getValue('first_name'))){
            $form_state->setErrorByName('first_name', $this->t('Error, The First Name Must Be A String'));
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
        // $form_state->getValue('picture')=> returns array of fids
        // The fid is the location in the database
        // This is the information that we ended up saving into our settings file data.
        // Fetch the array of the file stored temporarily in database 
        $picture = $form_state->getValue('picture');

        $data = array(
            'first_name' => $form_state->getValue('first_name'),
            'last_name' => $form_state->getValue('last_name'),
            'email' => $form_state->getValue('email'),
            'phone' => $form_state->getValue('phone'),
            'dob' => $form_state->getValue('dob'),
            'gender' => $form_state->getValue('gender'),
            'select' => $form_state->getValue('select'),
            'message' => $form_state->getValue('message'),
            'fid' => $picture[0] 
        );

        //when we pull the information into our program 
        //we are querying for the 'fid' located in the 'file_managed' table using the Entity::load method.
        //Load the object of the file by it's fid
        $file = File::load($picture[0]);
        //files uploaded in this manner are currently, by default, set to be temporary (status column in the "file_managed" table). 
        //You need to set the file to permanent to manually set status to 1 so they are not temporary.
        //Set the status flag permanent of the file object
        $file->setPermanent();
        //Save the file in database
        $file->save();

        $file_usage = \Drupal::service('file.usage'); 
        $file_usage->add($file, 'user_crud', 'file', $file->id());

        if(isset($_GET['id'])){
            //update data in database
            \Drupal::database()->update('user_crud_table')->fields($data)->condition('id', $_GET['id'])->execute();
        }else{
            //insert data to database
            \Drupal::database()->insert('user_crud_table')->fields($data)->execute();
        }
        
        //show message and redirect to list page
        \Drupal::messenger()->addStatus('Successfully saved');
        $url =new Url('user_crud.display_data');
        $response = new RedirectResponse($url->toString());
        $response->send();
    }


}
?>