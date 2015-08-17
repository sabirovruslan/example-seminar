<?php
/**
 * @author Sabirov Ruslan <sabirov@worksolutions.ru>
 */

abstract class Factory {

    protected function bonus () {
        return 0;
    }

    public function getSalary () {
        return 100 + $this->bonus();
    }

    abstract public function createOrder ();
}

abstract class Order {
    public $factory;
    protected $workerMens = [];

    public function __construct(Factory $factory) {

        $this->factory = $factory;
    }

    public function make () {
        /** @var WorkerMen $workerMen */
        foreach ($this->workerMens as $workerMen) {

            $workerMen->income($this);
        }
    }

    public  function attach (WorkerMen $worker) {

        $this->workerMens[] = $worker;
    }
}

class WorkerMen {
    private $money;
    public function __construct() {
        $this->money = 0;
    }
    public function income (Order $order) {
        $this->money  += $order->factory->getSalary();
    }
    public function showMoney () {
        echo $this->money;
    }
}

class WorkerUnion {

    static private $instance;

    private function __construct() {}

    static public function getInstance () {

        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = new WorkerUnion();

        return self::$instance;
    }

    public function getBmwFactory () {

        return new BmwFactory();
    }

    public function getLadaFactory () {

        return new LadaFactory();
    }

}

class BmwFactory extends Factory {

    public function createOrder() {

        return new BmwOrder($this);
    }


}

class LadaFactory extends Factory {

    public function bonus () {
        return 100;
    }

    public function createOrder() {

        return new LadaOrder($this);
    }
}


class BmwOrder extends  Order {


}

class LadaOrder extends Order {


}

$union = WorkerUnion::getInstance();


$workerMenBmw = new WorkerMen();
$bmwFactory = $union->getBmwFactory();
$orderBNmw = $bmwFactory->createOrder();
$orderBNmw1 = $bmwFactory->createOrder();
$orderBNmw2 = $bmwFactory->createOrder();


$orderBNmw->attach($workerMenBmw);
$orderBNmw->make();

$orderBNmw1->attach($workerMenBmw);
$orderBNmw1->make();

$orderBNmw2->attach($workerMenBmw);
$orderBNmw2->make();

echo "//////////////////////////////////////" . "\n";

$workerMenBmw->showMoney();

echo "//////////////////////////////////////" . "\n";

$workerMenLada = new WorkerMen();
$ladaFactory = $union->getLadaFactory();
$orderLada = $ladaFactory->createOrder();
$orderLada1 = $ladaFactory->createOrder();
$orderLada2 = $ladaFactory->createOrder();

$orderLada->attach($workerMenLada);
$orderLada->make();

$orderLada1->attach($workerMenLada);
$orderLada1->make();

$orderLada2->attach($workerMenLada);
$orderLada2->make();

echo "//////////////////////////////////////" . "\n";

$workerMenLada->showMoney();

echo "//////////////////////////////////////" . "\n";