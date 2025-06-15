<?php
// Start the session
session_start();

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

    public function signIn($email,$password) 
    {
        $this->email=$email;
        $this->password=$password;
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM user where email='$this->email'&&password='$this->password'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $_SESSION["id"]=$row["id"];
                $_SESSION["firstname"]=$row["firtname"];
               

                // echo $_SESSION["id"];
                // echo $_SESSION["firstname"];
                header("Location: ../View/index.php");
                
            }
        }
        else {
            echo "0 results";
        }
        
    }
    
    public function AddVol($fname,$lname,$email,$password,$pnum){
        $this->fname=$fname;
        $this->lname=$lname;
        $this->email=$email;
        $this->password=$password;
        $this->Pnum=$pnum;
        $conn = Database::getInstance()->getConnection();
        $sql = "INSERT INTO Volunteer (firtname,lastname,email,password,phonenum, role) 
                VALUES ('$this->fname', '$this->lname', '$this->email', '$this->password', '$this->Pnum','Volunteer')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../View/index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
        header("Location: ../View/index.php");
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