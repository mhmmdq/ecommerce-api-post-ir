<?php

namespace Mhmmdq\EcommerceApi;

class Factor {
    
    public function __construct(
        $companyName, $companyLogo, $companyPhone, $companyWebsite, $companyEmail, $sellerName, $sellerState, $sellerAddress, $sellerPostalCode, $orderID,
        array $products, $customerName, $customerState, $customerCity, $customerAddress, $customerPhone, $customerMessage, $customerPostalCode,
        $barcode, $code, $weight, $date, $time, $sendPost, $sendPostMaliyat , $stateCode
    )
    {
        $template = $this->loadTemplate();
        $table = $this->createOrderTable( $products );
        $template = str_replace([
            '{companyName}' , '{companyLogo}' , '{companyPhone}' , '{companyUrl}' , '{companyEmail}' , '{sellerName}' , '{sellerState}' , '{sellerAddress}' ,
            '{sellerPostalCode}', '{orderID}' , '{Table}' , '{customerName}' , '{customerState}' , '{customerCity}' , '{customerAddress}' , '{customerPhone}' ,
            '{customerMessage}' , '{customerPostalCode}' , '{barcode}' , '{code}' , '{weight}' , '{date}' , '{time}' , '{sendPost}' , '{sendPostMaliyat}' , '{stateCode}'
        ] , [
            $companyName , $companyLogo , $companyPhone , $companyWebsite , $companyEmail , $sellerName , $sellerState , $sellerAddress , $sellerPostalCode,
            $orderID , $table , $customerName , $customerState , $customerCity , $customerAddress , $customerPhone , $customerMessage , $customerPostalCode , $barcode,
            $code , $weight , $date , $time , $sendPost , $sendPostMaliyat , $stateCode
        ] , $template);

        echo $template;
    }

    protected function loadTemplate()
    {
        $file_handler = fopen( __DIR__ . '/templates/factor.template' , 'r' );
        return fread($file_handler , filesize( __DIR__ . '/templates/factor.template' ));
        fclose($file_handler);
    }

    protected function createOrderTable( array $products )
    {
        $output = '<table style="width: 100%;" class="products"><thead style="background:#ddd"><tr><th style="background:#ddd; border:1px solid #000">شرح محصول</th><th style="background:#ddd; border:1px solid #000">شعبه</th><th style="background:#ddd; border:1px solid #000">تعداد</th><th style="background:#ddd; border:1px solid #000">قیمت واحد (ريال)</th><th style="background:#ddd; border:1px solid #000">هزینه ارسال (ريال)</th><th style="background:#ddd; border:1px solid #000">قیمت کل (ريال)</th></tr></thead><tbody>';
        $i = 1;
        foreach( $products as $product )
        {
            if($i = 2) {
                $product['send_price'] = '-';
            }
            $i++;
            $output .= "<tr>";
            $output .= "<td class='product-name'><span class='name'>{$product['product_name']}</span></td>";
            $output .= "<td>{$product['seller']}</td>'";
            $output .= "<td class='product-quantity'><span>{$product['quantity']}</span></td>";
            $output .= "<td class='product-item-price'><span>{$product['item_price']}</span></td>";
            $output .= "<td class='product-item-price'><span>{$product['send_price']}</span></td>";
            $output .= "<td class='product-price'><span>{$product['price']}</span></td>";
            $output .= "<tr>";
        }

        $output .= '</tbody><tfoot style="font-size: 100%"><tr><td colspan="3" class="text-center" style="vertical-align: middle;background: #ccc">نوع پرداخت : آنلاین<br></td><td colspan="3" class="text-center" style="vertical-align: middle;line-height: 1.5;"><div style="position: relative">مجموع مبلغ قابل پرداخت توسط گیرنده (خریدار) :<br><bdi>0 ریال</bdi></div></td></tr></tfoot></table>';

        return $output;
    }



}