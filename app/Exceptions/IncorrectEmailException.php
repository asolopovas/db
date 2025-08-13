<?php

namespace App\Exceptions;

use Exception;

class IncorrectEmailException extends Exception
{
  public $status = 422;

  public $emails;
  public $location;

  public function __construct($emails, $location)
  {
    parent::__construct('');
    $this->message = $this->formatMessage($emails, $location);
  }

  public function formatMessage($emails, $location)
  {
    $emailsString = implode(",", $emails);

    return "The following emails in \"$location\" field: $emailsString, are invalid please check before continue.";
  }

}
