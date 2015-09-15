<?php
/**
 * @author Sabirov Ruslan <sabirov@worksolutions.ru>
 */


class Doctor {
    private $price;

    function __construct($price = 0) {
        $this->price = $price;
    }

    public function reception(Patient $patient) {
        $patient->debitMoney($this->price);
    }


}

class Patient {
    private $money;
    function __construct($money) {
        $this->$money = $money;
    }

    /**
     * @return mixed
     */
    public function getMoney() {
        return $this->money;
    }

    public function debitMoney ($sum) {
        if (intval($sum)) {
            throw new Exception ("Не верная стоимость");
        }
        if ($this->money < $sum) {
            throw new Exception ("Не достачно средств");
        }

        $this->money -= $sum;
    }


}

class MedicalHistory {

    private $listHistory;
    private $patient;

    public function __construct(Patient $patient) {
        $this->patient = $patient;
    }

    public function attach(Doctor $doctor) {
        $this->listHistory[] = $doctor;
    }

    public function notify () {
        /** @var Doctor $doctor */
        foreach ($this->listHistory as $doctor) {
            try {
                $doctor->reception($this->patient);
            } catch (MedicalHistoryException $e) {
                echo $e->getMessage();
            }

        }
    }

}

class MedicalHistoryException extends Exception {

}

$patient = new Patient(100);
$medicalHistory = new MedicalHistory($patient);
$doctorA = new Doctor(50);
$doctorB = new Doctor(50);
$doctorC = new Doctor(100);

$medicalHistory->attach($doctorA);
$medicalHistory->attach($doctorB);
$medicalHistory->attach($doctorC);

