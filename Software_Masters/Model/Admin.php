<?php

require_once 'User.php';
require_once 'Subject.php';
require_once 'Database.php';

class Admin extends User implements Subject{
    private array $events = [];
    private $event;

    public function notifyDonor(): void {}
    public function notifyVolunteer(): void {}
    public function showEvents(): array {
        return $this->events;
    }

    private $observers = [];

    public function registerObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function removeObserver(Observer $observer) {
        $this->observers = array_filter($this->observers, fn($o) => $o !== $observer);
    }

    public function notifyObservers(Event $event) {
        foreach ($this->observers as $observer) {
            $observer->update($event);
        }
    }

    public function addEvent(Event $event) {
        $name = $event->eventName;
        $date = $event->date;
        $location = $event->location;
        // INSERT SQL (example for 'donors' table)
        $conn = Database::getInstance()->getConnection();
        $sql = "INSERT INTO event (name, date, location) 
                VALUES ('$name', '$date', '$location')";

        if ($conn->query($sql) === TRUE) {
            echo "New donor added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        

        // Save event to DB, etc.
        $this->notifyObservers($event);  // Notify all registered Donors/Volunteers
    }
    // public function Add(Event $event){
    //     $this->event=$event;
    //     $conn = Database::getInstance()->getConnection();

    //     $name = $event->eventName;
    //     $date = $event->date;
    //     $location = $event->location;
    //     // INSERT SQL (example for 'donors' table)
    //     $sql = "INSERT INTO event (name, date, location) 
    //             VALUES ('$name', '$date', '$location')";

    //     if ($conn->query($sql) === TRUE) {
    //         echo "New donor added successfully";
    //     } else {
    //         echo "Error: " . $sql . "<br>" . $conn->error;
    //     }

    //     $conn->close();

    // }
}

?>