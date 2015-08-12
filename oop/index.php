<?php
/**
 * @author Sabirov Ruslan <sabirov@worksolutions.ru>
 */

abstract class Auction {

    protected $lot;
    protected $startTime;
    protected $price;
    /**
     * @var OfferManager|null
     */
    protected $winner;
    protected $customers = [];

    public function stop() {}

    public function start() {}

    public function __construct(Lot $lot) {

        $this->startTime = time();
        $this->lot = $lot;
        $this->price = $lot->getPrice();
    }



    public function addCustomer(Customer $customer)
    {
        $this->customers[] = $customer;

        $offerManager = new OfferManager($customer, $this);

        return $offerManager;
    }

    abstract public function validateOffer($offer);

    public function setWinner (OfferManager $offerManager) {

        $this->winner = $offerManager;
    }
}

class Lot {

    protected $price;

    public function __construct($price) {

        if (!$price > 0) {

            throw new Exception ("Лот не может иметь нулевую сумму");
        }

        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }
}

class Customer {

    protected $name;

    public function __construct($name) {

        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}

class PeriodAuction extends Auction {
    private $time;

    public function setLiveTime ($time) {

        $this->time = $time;
    }

    public function validateOffer($offer)
    {
        if (!$this->time) {
            throw new Exception ("Не уставовлено время жизни аукциона");
        }

        if ($offer <= $this->price) {

            throw new Exception ("Ставку можно только повышать");
        }

        if (time() > ($this->startTime + $this->time)) {

            throw new Exception ("Время вышло. " . is_null($this->winner) ?  "Нет победителя" : "Победитель: " . $this->winner->getCustomer()->getName());
        }
    }

    public function getWinner()
    {
        if (!$this->winner) {
            throw new Exception('Нет победителя');
        }
        return $this->winner;
    }
}

class LastBed extends Auction {

    public function validateOffer($offer)
    {
        // TODO: Implement validateOffer() method.
    }
}

class OfferManager {

    protected $customer;
    protected $auction;

    public function __construct(Customer $customer, Auction $auction)
    {
        $this->customer = $customer;
        $this->auction = $auction;

    }

    public function handUp ($offer) {

        try {
            $this->auction->validateOffer($offer);
            $this->auction->setWinner($this);
        } catch (Exception $e) {

            throw new Exception ("Не валидное предложение:" . $e->getMessage());
        }


    }

    public function getCustomer()
    {
        return $this->customer;
    }

}

$customerA = new Customer("A");
$customerB = new Customer("B");
$periodAuction = new PeriodAuction(new Lot(100));
$periodAuction->setLiveTime(10);
$offerManagerCustomerA = $periodAuction->addCustomer($customerA);
$offerManagerCustomerB = $periodAuction->addCustomer($customerB);

$offerManagerCustomerA->handUp(110);
sleep(1);
$offerManagerCustomerB->handUp(120);
sleep(1);

printf("%s\n", $periodAuction->getWinner()->getCustomer()->getName());







