<?php

namespace Mhmmdq\EcommerceApi;
use Mhmmdq\Request\Curl;
class GateWay {

        protected $ch;

        protected static $BaseUri = 'https://ecommerceapi.post.ir/api/company/';

        public function __construct(string $username ,string $password ,string $secretKey)
        {
            $this->ch = new Curl();
            
            $this->ch->curl_set_opt(CURLOPT_SSL_CIPHER_LIST , 'DEFAULT@SECLEVEL=1');
            
            $this->ch->useragent('GateWay Class V1.0');

            $apiKey = md5("{$username}{$password}{$secretKey}");
            
            $this->ch->curl_set_opt(CURLOPT_HTTPHEADER , [
                "username: {$username}",
                "password: {$password}",
                "apikey: {$apiKey}",
                "Content-Type:application/json",
            ]);
        }

        public function GetStates()
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__)->send();
        }

        public function GetStateCities( int $stateID )
        { 
            return $this->ch->post(self::$BaseUri . __FUNCTION__ . "?StateID={$stateID}" , )->send();
        }


        public function RegisterShop(string $ShopID = null , string $ShopUsername , string $Shopname , string $Phone , string $PostalCode ,
                                    string $ManagerName = null , string $ManagerFamily = null , string $ManagerNashnalID , string $ManagerNationalIDSerial,
                                    string $ManagerBirthDate ,string $Mobile , string $Email = null ,string $ReciptNumber = null, string $ReciptDate = null,
                                    string $StartDate, string $EndDate, string $WebSiteURL = null, string $CityID = null , string $AccountNumber = null ,
                                    string $AccountIBAN = null , string $AccountBranchName = null , string $NeedToCollect)
        {
            $this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                'ShopID' => $ShopID ,
                'ShopUsername' => $ShopUsername,
                'Shopname' => $Shopname,
                'Phone' => $Phone ,
                'PostalCode' => $PostalCode ,
                'ManagerName' => $ManagerName ,
                'ManagerFamily' => $ManagerFamily,
                'ManagerNashnalID' => $ManagerNashnalID,
                'ManagerNationalIDSerial' => $ManagerNationalIDSerial,
                'ManagerBirthDate' => $ManagerBirthDate , 
                'Mobile' => $Mobile ,
                'Email' => $Email ,
                'ReciptNumber' => $ReciptNumber,
                'ReciptDate' => $ReciptDate,
                'StartDate' => $StartDate,
                'EndDate' => $EndDate,
                'WebSiteURL' => $WebSiteURL,
                'CityID' => $CityID,
                'AccountNumber' => $AccountNumber,
                'AccountIBAN' => $AccountIBAN,
                'AccountBranchName' => $AccountBranchName,
                'NeedToCollect' => $NeedToCollect
            ]);
        }

        public function GetPrice($PriceClassJson)
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson($PriceClassJson);
        }

        public static function MakePrice($DestinationCityID,$NonStandardPackage,$ParcelServiceType,$ParcelValue,$PaymentType,$SMSService,$ShopID,$Weight) {

            return [
                'DestinationCityID' => $DestinationCityID,
                'NonStandardPackage' => $NonStandardPackage ,
                'ParcelServiceType' => $ParcelServiceType,
                'ParcelValue' => $ParcelValue,
                'PaymentType' => $PaymentType,
                'SMSService' => $SMSService,
                'ShopID' => $ShopID,
                'Weight' => $Weight,
            ];

        }

        public function AddRequest( $OrderID , $PriceClassJson , $CustomerNID , $CustomerName , $CustomerFamily , $CustomerMobile , $CustomerEmail , $CustomerPostalCode , $CustomerAddress , $ParcelContent )
        {
            if(!empty($CustomerNID))
            {
                return $this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                    'OrderID' => $OrderID,
                    'Price' => $PriceClassJson,
                    'CustomerNID' => $CustomerNID,
                    'CustomerName' => $CustomerName,
                    'CustomerFamily' => $CustomerFamily,
                    'CustomerMobile' => $CustomerMobile,
                    'CustomerEmail' => $CustomerEmail,
                    'CustomerPostalCode' => $CustomerPostalCode,
                    'CustomerAddress' => $CustomerAddress,
                    'ParcelContent' => $ParcelContent
                ]);
            }else {
                return $this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                    'OrderID' => $OrderID,
                    'Price' => $PriceClassJson,
                    'CustomerName' => $CustomerName,
                    'CustomerFamily' => $CustomerFamily,
                    'CustomerMobile' => $CustomerMobile,
                    'CustomerEmail' => $CustomerEmail,
                    'CustomerPostalCode' => $CustomerPostalCode,
                    'CustomerAddress' => $CustomerAddress,
                    'ParcelContent' => $ParcelContent
                ]);
            }

            
        }

        public function DeleteRequests( string $Barcode )
        {
            $result = json_decode($this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                ['Barcode' => $Barcode]
            ]) , true);
            switch($result['Result']) {
                case "0":
                    return 'با موفقیت حذف شد';
                    break;
                case "1":
                    return 'این بارکرد مربوط به شرکت نمیباشد';
                    break;
                case "2":
                    return 'امکان حذف بارکد وجود ندارد';
                    break;
                case "3":
                    return 'عدم وجود اطلاعات بارکد';
                    break;
                default:
                    return $result['Result'];
                    break;
            }
        }

        public function ReadyToCollectRequests( string $Barcode )
        {
            $result =  json_decode($this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                ['Barcode' => $Barcode]
            ]) , true);
            switch($result['Result']) {
                case "0":
                    return 'تغییر وضعیت موقق';
                    break;
                case "1":
                    return 'این بارکرد مربوط به شرکت نمیباشد';
                    break;
                case "2":
                    return 'امکان تغییر وضعیت  بارکد وجود ندارد';
                    break;
                case "3":
                    return 'عدم وجود اطلاعات بارکد';
                    break;
                default:
                    return $result['Result'];
                    break;
            }
        }

        public function SuspendRequests( string $Barcode )
        {
            $result =  json_decode($this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                ['Barcode' => $Barcode]
            ]) , true);
            switch($result['Result']) {
                case "0":
                    return 'تغییر وضعیت موقق';
                    break;
                case "1":
                    return 'این بارکرد مربوط به شرکت نمیباشد';
                    break;
                case "2":
                    return 'امکان تغییر وضعیت  بارکد وجود ندارد';
                    break;
                case "3":
                    return 'عدم وجود اطلاعات بارکد';
                    break;
                default:
                    return $result['Result'];
                    break;
            }
        }

        public function EditRequest( string $Barcode , int $ShopID , string $ParcelContent , string $CustomerNID , string $CustomerName ,
                                     string $CustomerAddress , string $CustomerEmail , string $CustomerMobile , string $CustomerPostalCode )
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                'Barcode' => $Barcode,
                'ShopID' => $ShopID,
                'ParcelContent' => $ParcelContent,
                'CustomerNID' => $CustomerNID,
                'CustomerName' => $CustomerName,
                'CustomerAddress' => $CustomerAddress,
                'CustomerEmail' => $CustomerEmail,
                'CustomerMobile' => $CustomerMobile,
                'CustomerPostalCode' => $CustomerPostalCode
            ]);
        }

        public function GetParcelList( int $ShopID , string $ReportDate )
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__ . "?ShopID={$ShopID}&ReportDate={$ReportDate}")->send();
        }

        public function GetParcelTrace( string $Barcode )
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__ . "?Barcode={$Barcode}")->send();
        }

        public function GetShopList()
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__)->send();
        }

        public function GetShopFullList()
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__)->send();
        }

        public function GetSettlementList( string $From , string $To )
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__ . "?Form={$From}&To={$To}")->send();
        }

        public function GetSettlementsItems( string $SettlementCode )
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__)->sendJson([
                'SettlementCode' => $SettlementCode,
                'SettlementDate' => '',
                'SettlementType' => ''
            ]);
        }

        public function GetSettlementDetails( string $SettlementCode )
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__ . "?SettlementCode={$SettlementCode}")->send();
        }

        public function GetCompanyInDebt()
        {
            return $this->ch->post(self::$BaseUri . __FUNCTION__)->send();
        }

}