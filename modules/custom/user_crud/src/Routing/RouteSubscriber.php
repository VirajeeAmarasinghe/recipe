<?php
namespace Drupal\user_crud\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events
 */
class RouteSubscriber extends RouteSubscriberBase{

    protected function alterRoutes(RouteCollection $collection){
        $route1 = $collection->get('user_crud.add_form');
        $route2 = $collection->get('user_crud.delete_form');

        if($route1){
            $route1->setRequirements([
                '_user_crud_access_check' => 'TRUE'
            ]);
        }
        if($route2){
            $route2->setRequirements([
                '_user_crud_access_check' => 'TRUE'
            ]);
        }
        
    }
}
?>