<?php
interface Payments
{
    function payment($sum);
}

class RegularPayment implements Payments
{
    function payment($sum)
    {
        return  "Regular realisation $sum\n</br>";
    }
}

class PaymentService
{
    function servicePayment($sum)
    {
        $sum = $sum-5;
        return "Payment Service realisation $sum with commision\n <br>";
    }
}

class Adapter implements Payments
{
    private $pay = null;
    function __construct(PaymentService $spay)
    {
        $this->pay = $spay;
    }

    function payment($sum)
    {
        return $this->pay->servicePayment($sum);
        //var_dump($this->pay); echo "</br>";
    }
}

$regularPay = new RegularPayment();
$alienPayObject= new PaymentService();

$alienPayWithApapter = new Adapter($alienPayObject);

function testPayments(Payments $payobject)
{
    return $payobject->payment(100);
    //var_dump($p); echo "</br>";
}
echo testPayments($regularPay);
echo testPayments($alienPayWithApapter);
