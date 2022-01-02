<?php
namespace Drupal\create_vocabulary\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\taxonomy\Entity\Vocabulary;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class VocabularyController
 * @package Drupal\create_vocabulary\Controller
 */
class VocabularyController extends ControllerBase{
    public function index(){
        $vocabularies = Vocabulary::loadMultiple();

        $header = array(
            'vid' => t('Vocabulary ID'),
            'description' => t('Description'),
            'name' => t('Name'),
            'view' => t('View'),
            'delete' => t('Delete'),
            'edit' => t('Edit'),
            'term' => t('Add Term')
        );

        $rows = array();

        foreach($vocabularies as $vocabulary){
            $url_delete = Url::fromRoute('create_vocabulary.delete_vocabulary', ['id' => $vocabulary->get('vid')], []);
            $url_edit = Url::fromRoute('create_vocabulary.add_vocabulary', ['id' => $vocabulary->get('vid')], []);
            $url_view = Url:: fromRoute('create_vocabulary.show_vocabulary', ['id' => $vocabulary->get('vid')], []);
            $url_add_term = Url::fromRoute('create_term.add_term', ['vocabulary_id' => $vocabulary->get('vid'), 'voc_id' => $vocabulary->get('vid')], []);
            $linkDelete = Link::fromTextAndUrl('Delete', $url_delete)->toString();
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit)->toString();
            $linkView = Link::fromTextAndUrl('View', $url_view)->toString();
            $linkAddTerm = Link::fromTextAndUrl('Add Term', $url_add_term)->toString();

            $rows[] = array(
                'vid' => $vocabulary->get('vid'),
                'description' => $vocabulary->get('description'),
                'name' => $vocabulary->get('name'),
                'view' => $linkView,
                'delete' => $linkDelete,
                'edit' => $linkEdit,
                'term' => $linkAddTerm
            );
        }

        $add = Url::fromUserInput("/admin/taxonomy/vocabularies/add");

        $text = "Add New Vocabulary";

        $form['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => t('No data found'),
            '#caption' => Link::fromTextAndUrl($text, $add)->toString()            
        ];

        return $form;
    }

    public function show($id){
        $vocabulary = Vocabulary::load($id);  
        $name = $vocabulary->get('name');
        $description = $vocabulary->get('description');

        $url_display = Url::fromRoute('create_vocabulary.display_vocabularies', [], []);
        $linkDisplay = Link::fromTextAndUrl('Go Back', $url_display)->toString();

        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties([
            'vid' => $id
        ]);

        $terms_html="Terms <br/>";

        foreach($terms as $term){
            $url_edit = Url::fromRoute('create_term.add_term', ['vocabulary_id' => $id, 'id' =>$term->tid->value, 'voc_id' => $id, 'update' => true], []);
            
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit)->toString();

            $url_delete = Url::fromRoute('create_term.delete_term', ['vocabulary_id' => $id, 'id' =>$term->tid->value], []);
            
            $linkDelete = Link::fromTextAndUrl('Delete', $url_delete)->toString();

            $terms_html=$terms_html.$term->name->value.' '.$linkEdit.' '.$linkDelete.'<br/>';
        }

        if(count($terms)===0){
            $terms_html = "No Terms Found. <br/>";
        }

        return [
            '#type' => 'markup',
            '#markup' => "<h1>Vocabulary ID: $id</h1><br>
            <p>Vocabulary Name: $name</p>
            <p>Description: $description</p>
            $terms_html
            $linkDisplay"
        ];
    }
}