<?php
namespace Drupal\events_and_hooks\EventSubscriber;

use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AnotherConfigEventsSubscriber implements EventSubscriberInterface{
    protected $messenger;

    public function __construct(MessengerInterface $messenger){
        $this->messenger = $messenger;
    }

    public static function getSubscribedEvents(){
        return [
            ConfigEvents::SAVE => ['configSave', 100],
            ConfigEvents::DELETE => ['configDelete', -100]
        ];
    } 
    
    public function configSave(ConfigCrudEvent $event){
        $config = $event->getConfig();
        $this->messenger->addStatus(__CLASS__. ' - Saved config: ' . $config->getName());
    }

    public function configDelete(ConfigCrudEvent $event){
        $config = $event->getConfig();
        $this->messenger->addStatus(__CLASS__. ' - Deleted config: ' . $config->getName());
    }
}
?>