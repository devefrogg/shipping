# Shipping
Generate PDF Label for Chronpost or Colissimo,
get only the pdf to print and the tracking number

#Install with composer :
```php
composer require tlissak/shipping
```


Colissimo usage :
```php
use Shipping\Colissimo ;

$colissimo = new Colissimo('accountNumber','accountPassword') ;

$payloads = [
    'label'=>'CMD0001',
    'weight'=>1,
    'date'=>date('Y-m-d'),
    'shipper'=>[
        'companyName' => 'Your Company Name',
        'line2'       => '10 Postal Address',
        'countryCode' => 'FR',
        'city'        => 'PARIS',
        'zipCode'     => '75000',
        'email'       => '' //your_email_address@email.email
    ]
    ,'recipient'=>[
        'companyName' => 'recipient Company',
        'lastName'      => 'recipient Last name', //
        'firstName'     => 'recipient First name', //
        'line2'       => 'recipient Postal address' ,// Address
        'line3'       => '', //Additional Information
        'countryCode' => 'FR', //
        'city'        => 'PARIS', //
        'zipCode'     => '75000', //
        'phone'=>'0600000000', // 10 digits 
         'email'=>'', //recipient address
    ]
] ;


$response = $colissimo->generateLabel($payloads);
echo $response['tracking'] ; 
file_put_contents("colissimo.pdf",$response["pdf"]) ;
```

For Chronopost :
```php
use Shipping\Chronopost ;

$payloads = [

    'shipper' => [
        'Adress1'=>'Your postal address'
        ,'Adress2'=>''
        ,'City'=>'Paris'
        ,'Civility'=>'M'
        ,'ContactName'=>'Your contact name' //Company
        ,'Country'=>'FR'
        ,'CountryName'=>'FRANCE'
        ,'Email'=>'your_email_address@email.email'
        ,'MobilePhone'=>''
        ,'Name'=>'Your name' //Company
        ,'Name2'=>''
        ,'Phone'=>'0600000000' // 10 digits
        ,'PreAlert'=>'0'
        ,'ZipCode'=>'75000'
    ]
    ,'customer'=>[
        'Adress1'=>'Your recepient postal address'
        ,'Adress2'=>''
        ,'City'=>'PARIS'
        ,'Civility'=>'M'
        ,'ContactName'=>'Contact Name'//Lissak
        ,'Country'=>'FR'
        ,'CountryName'=>'FRANCE'
        ,'Email'=>'email@address.com' //
        ,'MobilePhone'=>'0600000000' // 10 digits
        ,'Name'=>'First name'
        ,'Name2'=>'Last Name'
        ,'Phone'=>''
        ,'PreAlert'=>'1'
        ,'ZipCode'=>'75000'
    ]
    ,'recipient'=>[]
    ,'ref'=>[
        'shipperRef'=>'BC0000000000001'
    ]
    ,'skybill'=>[
        'productCode'=>'01' // For Chrono relay 13H use 86
        ,'shipDate'=>date('c')
        ,'shipHour'=>date('G')
        ,'weight'=>1 //KGM
        ,'service' => '0'
        ,'objectType'=>'MAR' //DOC / MAR Document ou Marchandise
        
    ]
] ;
$payloads['recipient'] = $payloads['customer'];

$chronopost = new Chronopost('TODO','TODO');


try {
    $result = $chronopost->genereEtiquette($payloads);
} catch (Exception $soapFault) {
    //var_dump($soapFault);
    exit($soapFault->faultstring);
}

if ($result->return->errorCode) {
    echo 'Erreur n° ' . $result->return->errorCode . ' : ' . $result->return->errorMessage;

} else {
    
    echo $result->return->skybillNumber ; 
file_put_contents("chronopost.pdf",$result->return->skybill) ;


}

```