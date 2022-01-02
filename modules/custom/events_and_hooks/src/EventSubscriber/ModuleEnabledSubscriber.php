<?php
namespace Drupal\events_and_hooks\EventSubscriber;

use Drupal\Core\Config\ConfigEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ModuleEnabledSubscriber implements EventSubscriberInterface{
    public static function getSubscribedEvents(){
        return [
            ConfigEvents::SAVE => 'configDidSave',
            ConfigEvents::DELETE => 'configDidDelete'
        ];
    }

    public function configDidSave(){
        \Drupal::messenger()->addMessage("Config totally saved.");
    }

    public function configDidDelete(){
        \Drupal::messenger()->addMessage("Config deleted.");
    }
}
?>