<?php
/**
 * @author Sabirov Ruslan <sabirov@worksolutions.ru>
 */

class Entity {

    private $listeners = [];
    private $data;

    public function __set($name, $value) {
        $this->data[$name] = $value;
        $this->notify($name);
    }

    public function __get($name) {
        return $this->data[$name];
    }

    public function toString () {
        return json_encode($this->data);
    }

    public function attach (ChangeAnalyzer $listener) {
        $this->listeners[] = $listener;
    }

    public function notify($prop) {
        /** @var ChangeAnalyzer $listener */
        foreach ($this->listeners as $listener) {
            $listener->update($prop, $this);
        }
    }

}

class ChangeAnalyzer {
    public function update ($prop, Entity $entity) {
        $time = new DateTime();
        $time = $time->format('H:i:s');
        echo "Prop $prop, Value {$entity->$prop}, Time $time \n";
    }
}

$listener = new ChangeAnalyzer();
$entityA = new Entity();
$entityA->attach($listener);

$entityA->name = 'Оксана';
$entityA->name = 'Руслан';
$entityA->wre = 'Руслан';

