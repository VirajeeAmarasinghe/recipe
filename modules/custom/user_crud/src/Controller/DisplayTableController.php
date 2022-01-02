<?php
namespace Drupal\user_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class DisplayTableController
 * @package Drupal\user_crud\Controller
 */
class DisplayTableController extends ControllerBase{
    public function index(){
        //create table header
        $header_table = array(
            'id' => t('ID'),
            'first_name' => t('First Name'),
            'last_name' => t('Last Name'),
            'email' => t('Email'),
            'phone' => t('Phone'),
            'dob' => t('Date of Birth'),
            'gender' => t('Gender'),
            'view' => t('View'),
            'delete' => t('Delete'),
            'edit' => t('Edit')
        );

        //get data from database
        $query = \Drupal::database()->select('user_crud_table', 'm');
        $query->fields('m', ['id', 'first_name', 'last_name', 'email', 'phone', 'dob', 'gender']);
        $results = $query->execute()->fetchAll();

        $rows = array();

        foreach($results as $data){
            $url_delete = Url::fromRoute('user_crud.delete_form', ['id' => $data->id], []);
            $url_edit = Url::fromRoute('user_crud.add_form', ['id' => $data->id], []);
            $url_view = Url:: fromRoute('user_crud.show_data', ['id' => $data->id], []);
            $linkDelete = Link::fromTextAndUrl('Delete', $url_delete)->toString();
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit)->toString();
            $linkView = Link::fromTextAndUrl('View', $url_view)->toString();

            //get data
            $rows[] = array(
                'id' => $data->id,
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'phone' => $data->phone,
                'dob' => $data->dob,
                'gender' => $data->gender,
                'view' => $linkView,
                'delete' => $linkDelete,
                'edit' => $linkEdit
            );
        }

        $add = Url::fromUserInput("/admin/user_crud/add");

        $text = "Add New User";
        
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
}
?>