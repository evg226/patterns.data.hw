<?php

/**
 * abstract Strategy
 */
interface IPayService{
    public function pay(string $phone,int $sum):string;
}

/**
 * concrete Strategies
 */
class YaPay implements IPayService{
    private $name;
    public function __construct()
    {
        $this->name='Yandex Pay';
    }

    public function pay(string $phone, int $sum):string
    {
        return "You have paid sum: $sum by $this->name";
    }
}
class Qiwi implements IPayService{
    private $name;
    public function __construct()
    {
        $this->name='Qiwi';
    }

    protected function doService(string $phone, int $sum){
        //do something
    }

    public function pay(string $phone, int $sum):string
    {
        $this->doService($phone,$sum);
        return "You have paid sum: $sum by $this->name";
    }
}
class WebMoney implements IPayService{
    private $name;
    private $currency;
    public function __construct(int $currency)
    {
        $this->name='WebMoney';
        $this->currency=$currency;
    }

    protected function convertMoney( int $sum){
        return round($sum / $this->currency);
    }

    public function pay(string $phone, int $sum):string
    {
        $sumUSD=$this->convertMoney($sum);
        return "You have paid sum: $sumUSD by $this->name";
    }
}

/**
 * Context
 */
class Order {
    /**
     * @var IPayService
     */
    private $payService;

    private $phone;
    private $sum;
    public function __construct(IPayService $payService,string $phone,int $sum)
    {
        $this->payService=$payService;
        $this->phone=$phone;
        $this->sum=$sum;
    }

    public function makePay(){
        echo "\nTrying to pay order #$this->phone\n";
        echo $this->payService->pay($this->phone,$this->sum);
        echo "\n";
    }
}

$yaPay=new YaPay();
$qiwi=new Qiwi();
$webMoney=new WebMoney(104);

$orders = [
    new Order($yaPay,'945-949-67-56',4500),
    new Order($qiwi,'978-996-77-59',7200),
    new Order($webMoney,'945-996-76-54',14300),
    new Order($yaPay,'945-999-66-76',560),
] ;
foreach ($orders as $order){
    $order->makePay();
}

