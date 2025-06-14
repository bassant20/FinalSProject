<?php

require_once 'User.php';
require_once 'Database.php';


class Volunteer extends User implements Observer{
    private float $hoursWorked;
    private array $availability = []; // array of DateTime objects

    // public function __construct(int $id, string $name, string $role, string $email, string $password, int $prnum, float $hoursWorked, array $availability) {
    //     parent::__construct($id, $name, $role, $email, $password, $prnum);
    //     $this->hoursWorked = $hoursWorked;
    //     $this->availability = $availability;
    // }

    public function generateCertificate(Event $event): void {}
    
    public function update(Event $event) {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT email FROM volunteer";
        $result = $conn->query($sql);

        $emails = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $emails[] = $row["email"];
                $email = $row["email"]; 
        $subject = "New Event: " . $event->eventName;
        $body = "Hello ,<br><br>A new event titled <strong>{$event->eventName}</strong> has been added.<br>Location: {$event->location}<br>Date: {$event->date}<br><br>Log in to enroll as a volunteer.";
        sendEmail($email, $subject, $body);
            }
        } else {
            echo "No volunteer found.";
        }

       
    }
}

?>