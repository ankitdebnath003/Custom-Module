<?php

namespace Drupal\config_form;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * This class is used to validate full name, phone number, email address and gender 
 * of an user and return the error message if any otherwise returns TRUE.
 * 
 * @package Drupal\config_form
 * 
 * @author Ankit Debnath <ankit.debnath@innoraft.com>
 */
class FormValidator 
{
  use StringTranslationTrait;
  
  /**
   * This function is used to validate the full name of the user.
   *
   * @param string $name
   *   Stores the full name from the form.
   * 
   * @return string|boolean 
   *   If the validation got some error then returns the error message otherwise
   *   returns TRUE.
   */
  public function nameValidation(string $name) {
    if (!strlen($name)) {
      return 'Please Enter Your Full Name.';
    }
    if (!preg_match('/^[A-Za-z\s]*$/', $name)) {
      return 'Please enter a valid name. Name does not contain any number or special character.';
    }
    if (strlen($name) <= 3) {
      return 'Please enter a valid name. Name length is too short.';
    }
    return TRUE;
  }
  
  /**
   * This function is used to validate the indian phone number of the user.
   *
   * @param string $phone
   *   Stores the phone number from the form.
   * 
   * @return string|boolean 
   *   If the validation got some error then returns the error message otherwise
   *   returns TRUE.
   */
  public function phoneValidation(string $phone) {
    if (!strlen($phone)) {
      return 'Please Enter Your Phone Number.';
    }
    if (!preg_match('/^\+91[6-9][0-9]{9}$/', $phone)) {
      return 'Please enter a 10 digit indian phone number starting with +91.';
    }
    return TRUE;
  }
  
  /**
   * This function is used to validate the email address of the user and check
   * if the domain of the email id is in the allowlist or not.
   *
   * @param string $email
   *   Stores the email from the form.
   * @param array $domains
   *   Stores the domains that the email id should allow.
   * 
   * @return string|boolean 
   *   If the validation got some error then returns the error message otherwise
   *   returns TRUE.
   */
  public function emailValidation(string $email, array $domains) {
    if (!strlen($email)) {
      return 'Please Enter Your Email Address.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return 'Please enter valid email address.';
    }

    $pos = strpos($email, '@');
    $last_dot_position = strrpos($email, '.');
    $domain_name = substr($email, $pos + 1, $last_dot_position - $pos - 1);
    if (!in_array($domain_name, $domains)) {
      return 'Domain is not accepted. Please use public domains like gmail, yahoo, etc.';
    }
    $extension = substr($email, $last_dot_position);
    if ($extension != '.com') {
      return 'Only domains with .com extension is allowed.';
    }

    return TRUE;
  }

  /**
   * Thisk function is used to validate the gender.
   *
   * @param mixed $gender
   *   Stores either the gender or NULL if nothing from the radio options has 
   *   been selected.
   * 
   * @return string|bool
   *   Based on the validation of the gender.
   */
  public function genderValidation(mixed $gender) {
    if (!$gender) {
      return 'Please Select Your Gender.';
    }
    return TRUE;
  }
}