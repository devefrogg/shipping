<?

namespace Shipping;

use Opportus\SlsClient\Client;


class Colissimo{

    public static $tracking = 'https://www.laposte.fr/outils/suivre-vos-envois?code=' ;

    public static $user   ;
    public static $pass   ;

    public function __construct($user,$password){
        self::$user=$user;
        self::$pass=$password ;
    }

    public function GenerateLabel($payload){

        $requestParameters = $this->wrapper($payload);
        $cl = Client::create();

        $res = $cl->GenerateLabel($requestParameters);

        if ($res->getMessageId() == '0') {
            return ['tracking'=>$res->getParcelNumber(),'pdf'=>$res->getLabel() /*<binary attachment>*/] ;
        }else{
            throw new \Exception( $res->getMessageId() . ' '. $res->__toString() , 506 );
        }
    }


    public function wrapper($payload){

        $recipient = $payload['recipient'] ;

        if ((strpos($recipient['phone'],'06')===0
             || strpos($recipient['phone'],'07')===0 )
            && strlen($recipient['phone']) === 10 ) {
            $recipient['mobileNumber'] = $recipient['phone'] ;
        }else{
            $recipient['phoneNumber'] = $recipient['phone'] ;
        }

        unset($recipient['phone']) ;
        unset($recipient['email']) ;
        $recipient['email'] = $payload['recipient']['email'] ;

        $requestParameters = [
            'contractNumber' => self::$user,
            'password'       => self::$pass,
            'outputFormat'   => [
                'x'                  => '0',
                'y'                  => '0',
                'outputPrintingType' => 'PDF_A4_300dpi',
            ],
            'letter' => [

                'service' => [
                    'productCode' => 'DOM', // Domicile
                    'depositDate' => $payload['date'],
                ],
                'parcel' => [
                    'weight' => $payload['weight'],
                ],
                'sender' => [
                    'address' => $payload['shipper'],
                ],
                'addressee' => [
                    'addresseeParcelRef' =>  $payload['label'],
                    'address' => $recipient,
                ],
            ],
        ];


        return $requestParameters ;
    }




}
