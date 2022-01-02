<?php
namespace Drupal\user_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class MyDataController
 * @package Drupal\user_crud\Controller
 */
class MyDataController extends ControllerBase{

    /**
     * @param [type] $id
     * @return array
     */
    public function show($id){
        $conn = Database::getConnection();

        $query = $conn->select('user_crud_table', 'm')
        ->condition('id', $id)
        ->fields('m');

        $data = $query->execute()->fetchAssoc();

        $full_name = $data['first_name'].' '.$data['last_name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $dob = $data['dob'];
        $gender = $data['gender']=='M' ? 'Male' : 'Female';
        $message = $data['message'];

        $file = File::load($data['fid']);
        $picture = $file->createFileUrl();

        $url_display = Url::fromRoute('user_crud.display_data', [], []);
        $linkDisplay = Link::fromTextAndUrl('Go Back', $url_display)->toString();

        return [
            '#type' => 'markup',
            '#markup' => "<h1>$full_name</h1><br>
            <img src='$picture' width='100' height='100' /><br>
            <p>$email</p>
            <p>$phone</p>
            <p>$dob</p>
            <p>$gender</p>
            <p>$message</p><br>
            $linkDisplay"
        ];

    }
}
?>