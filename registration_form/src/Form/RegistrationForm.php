<?php

namespace Drupal\registration_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;

/**
 * Provides the form for adding employees.
 */
class RegistrationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    
    $form['user_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User Name'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#default_value' =>  '',
    ];
	
	$form['user_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User Id'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#default_value' => '',
    ];
	 $form['user_email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User Email'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#default_value' => '',
    ];
	
	
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#default_value' => $this->t('Register') ,
    ];
	
	//$form['#validate'][] = 'employeeFormValidate';

    return $form;

  }
  
   /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {
	//  print_r("######");exit;
       $field = $form_state->getValues();
	   
		$fields["user_name"] = $field['user_name'];
		if (!$form_state->getValue('user_name') || empty($form_state->getValue('user_name'))) {
            $form_state->setErrorByName('user_name', $this->t('Provide User Name'));
        }
		
		
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {
	try{
		$conn = Database::getConnection();
		
		$field = $form_state->getValues();
	   
		$fields["user_name"] = $field['user_name'];
		$fields["user_id"] = $field['user_id'];
		$fields["user_email"] = $field['user_email'];
		
		  $conn->insert('register_user')
			   ->fields($fields)->execute();
		  \Drupal::messenger()->addMessage($this->t('The User data has been succesfully saved'));
		 
	} catch(Exception $ex){
		\Drupal::logger('registration_form')->error($ex->getMessage());
	}
    
  }

}