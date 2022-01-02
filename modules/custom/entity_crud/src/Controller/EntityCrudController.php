<?php
namespace Drupal\entity_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class EntityCrudController
 * @package Drupal\entity_crud\Controller
 */
class EntityCrudController extends ControllerBase{
    public function index(){
        //create table header
        $header_table = array(
            'id' => t('ID'),
            'name' => t('Name'),
            'description' => t('Description'),            
            'view' => t('View'),
            'delete' => t('Delete'),
            'edit' => t('Edit')
        );

        //load entity_crud entities
        $entity_cruds = entity_load('entity_crud');

        $rows = array();

        foreach($entity_cruds as $entity_crud){
            $url_delete = Url::fromRoute('entity_crud.delete_form', ['id' => $entity_crud->id], []);
            $url_edit = Url::fromRoute('entity_crud.add_form', ['id' => $entity_crud->id], []);
            $url_view = Url:: fromRoute('entity_crud.show_data', ['id' => $entity_crud->id], []);
            $linkDelete = Link::fromTextAndUrl('Delete', $url_delete)->toString();
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit)->toString();
            $linkView = Link::fromTextAndUrl('View', $url_view)->toString();

            //get data
            $rows[] = array(
                'id' => $entity_crud->id,
                'name' => $entity_crud->name,
                'description' => $data->description,                
                'view' => $linkView,
                'delete' => $linkDelete,
                'edit' => $linkEdit
            );
        }

        $add = Url::fromUserInput("/entity_crud/add");

        $text = "Add New Entity Crud";
        
        //render table
        $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No data found'),
            '#caption' => Link::fromTextAndUrl($text, $add)->toString()
        ];

        return $form;
    }

    public function show($id){
        $entity_crud = entity_load('entity_crud', [$id]);
        
        $name = $entity_crud->name;
        $description = $entity_crud->description;
        
        $url_display = Url::fromRoute('entity_crud.display_data', [], []);
        $linkDisplay = Link::fromTextAndUrl('Go Back', $url_display)->toString();

        return [
            '#type' => 'markup',
            '#markup' => "
            <p>ID: $id</p>
            <p>Name: $name</p>
            <p>Description: $description</p>            
            $linkDisplay"
        ];

    }
}
?>