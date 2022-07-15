<?php 
  
  require 'vendor/autoload.php';


  function sendemail($site){
      $email = new \SendGrid\Mail\Mail(); 
      $email->setFrom(FROM, $site." is DOWN");
      $email->setSubject($site." is DOWN");
      $email->addTo(TO, $site." is DOWN");
      $email->addCC(CC, $site." is DOWN");
      $email->addContent("text/plain", $site." is DOWN");
      $email->addContent("text/html","<strong>$site IS DOWN </strong></br>".date("m/d/Y H:i:s"));
      
      $sendgrid = new \SendGrid(SENDGRID_API_KEY);
      
      try{
        
        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
        
      } catch(Exception $e){
        echo 'Caught exception: '.$e-getMessage(). "\n";
      }

    }
    
     
?>