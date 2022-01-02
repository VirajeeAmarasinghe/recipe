<?php
namespace Drupal\pagination;

/**
 * This class returns pagination
 */

class Pagination{


  protected $pager_manager;
  
  
  public function __construct($pager_manager) { 
    $this->pager_manager=$pager_manager;
  }  

  public function getPage($number_of_items, $items_per_page){
    $pager = $this->pager_manager->createPager($number_of_items, $items_per_page);
    $page = $pager->getCurrentPage();
    return $page;
  }

  
}

?>