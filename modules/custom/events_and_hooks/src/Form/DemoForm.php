<?php
namespace Drupal\events_and_hooks\Form;

use Drupal\events_and_hooks\Event\DemoEvent;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DemoForm extends FormBase{

    protected $eventDispatcher;

    public function __construct(ContainerAwareEventDispatcher $eventDispatcher){
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function create(ContainerInterface $container){
        return new static(
            $container->get('event_dispatcher')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFormID(){
        return 'demo_form';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state){
        $config = $this->config('events_and_hooks.demo_form_config');
        $form['my_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('My name'),
            '#default_value' => $config->get('my_name')
        ];
        $form['my_website'] = [
            '#type' => 'textfield',
            '#title' => $this->t('My website'),
            '#default_value' => $config->get('my_website')
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('save'),
            '#button_type' => 'primary'
        ];
        return $form;
    }

    /**
     *{@inheritDoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state){        
        $config = $this->config('events_and_hooks.demo_form_config');
        \Drupal::configFactory()->getEditable('events_and_hooks.demo_form_config')->set('my_name', $form_state->getValue('my_name'))
        ->set('my_website', $form_state->getValue('my_website'));
                
        $e = new DemoEvent($config);
        $event = $this->eventDispatcher->dispatch('demo_form.save', $e);
        $newData = $event->getConfig()->get();

        //Using hook
        // $configData = $config->get();
        // $newData = \Drupal::service('module_handler')->invokeAll('demo_config_save', array($configData));
        
        $config->merge($newData);        

        \Drupal::configFactory()->getEditable('events_and_hooks.demo_form_config')->save();
    }

    /**
     * {@inheritDoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state){}
}
?>