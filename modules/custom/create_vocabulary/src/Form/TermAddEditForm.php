<?php
namespace Drupal\create_vocabulary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\taxonomy\Entity\Vocabulary;
use \Drupal\taxonomy\Entity\Term;

class TermAddEditForm extends FormBase{

    /**
     * {@inheritDoc}
     *
     * @return string
    */
    public function getFormId(){
        return 'create_vacabulary_term_add_edit_form';
    }

    /**
     * {@inheritDoc}
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function buildForm(array $form, FormStateInterface $form_state){
        $vocabulary = null;  

        if(isset($_GET['voc_id'])){
            $vocabulary = Vocabulary::load($_GET['voc_id']); 
  
            
            if($vocabulary!==null){ 
                $term = null;
                $parents = [];
                $parent_array = [];
                if(isset($_GET['id'])){
                    $term = Term::load($_GET['id']);

                    $parents = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($_GET['id']);
                    
                    foreach($parents as $parent){
                        array_push($parent_array, $parent->id());
                    } 
                }  
                
                $form['name'] = [
                    '#type' => 'textfield',
                    '#title' => $this->t('Term Name'),
                    '#required' => TRUE,
                    '#size' => 60,
                    '#default_value' => (isset($_GET['id'])) ? $term->name->value : '',
                    '#maxlength' => 128,
                    '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
                ]; 

                $form['vid'] = [
                    '#type' => 'textfield',
                    '#title' => $this->t('Term Vocabulary'),
                    '#required' => TRUE,
                    '#size' => 60,
                    '#default_value' => $_GET['voc_id'],
                    '#maxlength' => 128,
                    '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12'],
                    '#attributes' => array('readonly' => 'readonly'),
                ];

                $associate_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties([
                    'vid' => $_GET['voc_id']
                ]);                

                $parent_terms[0] = 'Select a parent term';                 
                
                foreach($associate_terms as $associate_term){  
                    $parent_terms[$associate_term->tid->value] = $associate_term->name->value;                    
                } 

                $form['parent_term'] = [
                    '#type' => 'select',
                    '#title' => $this->t('Select Parent Term'),
                    '#options' => $parent_terms,                    
                    '#multiple' => TRUE,
                    '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12'],
                    '#default_value' => ((!is_null($term)) && (count($parent_array)>0)) ? $parent_array : 0
                ];
                

                $form['submit'] = [
                    '#type' => 'submit',
                    '#value' => $this->t('save'),
                    '#button_type' => 'primary'
                ];

                $link = Url::fromUserInput('/admin/taxonomy/vocabularies/index');

                $form['cancel'] = [
                    '#markup' => Link::fromTextAndUrl(t('Back to page'), $link, [
                        'attributes' => ['class' => 'button']])->toString()
                    ];  
                    
                    
                return $form;
            }else{
                $link = Url::fromUserInput('/admin/taxonomy/vocabularies/index');

                $markup = "Error Occurred! <br />";

                $form['cancel'] = [
                    '#markup' => $markup. Link::fromTextAndUrl(t('Back to page'), $link, [
                        'attributes' => ['class' => 'button']])->toString()
                ];  
                
                return $form;
            }
            
            
        }
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
        $vocabulary_id = $form_state->getValue('vid');

        $parent_term = $form_state->getValue('parent_term'); 

        foreach ($parent_term as $key => $value) {
            if($parent_term[$key]==="Select a parent term"){
                unset($parent_term[$key]); 
            }            
        }                         

        $term = null;

        if(isset($_GET['id'])){
            $term = Term::load($_GET['id']);  
            $term->set('name', $name);
            $term->set('parent', $parent_term);                      
        }else{
            $term = Term::create(array(
                'name' => $name,
                'vid' => $vocabulary_id,  
                'parent' => $parent_term              
            ));          
        }

        $term->save();

        if(isset($_GET['update'])){
            \Drupal::messenger()->addStatus('Successfully updated');
            $url =new Url('create_vocabulary.show_vocabulary', ['id' => $vocabulary_id]);
            $response = new RedirectResponse($url->toString());
            $response->send();
        }else{
            \Drupal::messenger()->addStatus('Successfully saved');
            $url =new Url('create_vocabulary.display_vocabularies');
            $response = new RedirectResponse($url->toString());
            $response->send();
        }
        
    }
}
?>