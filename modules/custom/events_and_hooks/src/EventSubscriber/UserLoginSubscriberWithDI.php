<?php
namespace Drupal\events_and_hooks\EventSubscriber;

use Drupal\events_and_hooks\Event\UserLoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserLoginSubscriberWithDI implements EventSubscriberInterface{
    public static function getSubscribedEvents(){ 
        return [
            UserLoginEvent::EVENT_NAME => 'onUserLogin'
        ];
    }

    public function onUserLogin(UserLoginEvent $userLoginEvent){
        $message = "Welcome, your account was created on " . date('d/m/Y', $userLoginEvent->account->getCreatedTime());
        \Drupal::messenger()->addMessage($message);
    }
}
?>