<?php

class Event {
    public int $eventID;
    public string $eventName;
    public $date;
    public string $location;
    public array $volunteers = [];
    public array $donors = [];

    public function __construct(/*int $eventID,*/ string $eventName, string $date, string $location) {
        // $this->eventID = $eventID;
        $this->eventName = $eventName;
        $this->date = $date;
        $this->location = $location;
    }

    public function addVolunteer(Volunteer $v): void {
        $this->volunteers[] = $v;
    }

    public function addDonor(Donor $d): void {
        $this->donors[] = $d;
    }

    public function showVolunteers(): array {
        return $this->volunteers;
    }

    public function showDonors(): array {
        return $this->donors;
    }
}

?>