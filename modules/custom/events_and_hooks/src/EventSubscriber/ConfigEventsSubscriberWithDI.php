<?php
namespace Drupal\events_and_hooks\EventSubscriber;

use \Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConfigEventsSubscriberWithDI implements EventSubscriberInterface{
    public static function getSubscribedEvents(){
        $events['demo_form.save'][] = array('onConfigSave', 0);
        return $events;
    } 
    
    public function onConfigSave($event){ 
        $config = $event->getConfig(); 
        $name_website = $config->get('my_name') . " / " . $config->get('my_website');
        \Drupal::configFactory()->getEditable('events_and_hooks.demo_form_config')->set('my_name_website', $name_website);
    }
}
?>