<?php
namespace Drupal\events_and_hooks\Event;

use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Config\Config;

class DemoEvent extends Event{
    protected $config;

    public function __construct(Config $config){
        $this->config = $config;
    }

    /**
     * Getter for the config object     *
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     * Setter for the config object
     */
    public function setConfig($config){
        $this->config = $config;
    }
}
?>