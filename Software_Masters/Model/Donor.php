<?php
// Start the session
session_start();
?>
<?php
require_once 'User.php';
require_once 'Donation.php';
require_once 'Observer.php';
require_once 'EmailNotifier.php';
require_once 'Database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';  // adjust the path if needed

class Donor extends User implements Observer{

    protected array $donations = [];
    
    public function signIn($email,$password) 
    {
        $this->email=$email;
        $this->password=$password;
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT id FROM donors where email='$this->email'&&password='$this->password'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $_SESSION["id"]=$row["id"];
                echo $_SESSION["id"];
            }
        }
        else {
            echo "0 results";
        }
        
    }
    public function getUser(int $id) {
        $this->id=$id;
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT id, firstname, lastname, role, phonenum FROM donors where id='$this->id'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $_SESSION["id"]=$this->id;
                $this->fname=$row["firstname"];
                $this->lname=$row["lastname"];
                $this->role=$row["role"];
                $this->Pnum=$row["phonenum"];
            }
        }
        else {
            echo "0 results";
        }
        
    }
    


    public function showDonations(): array {
        return $this->donations;
    }
    public function addDonation(Donation $donation): void {
        $this->donations[] = $donation;
    }
    public function update(Event $event) {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT email FROM donors";
        $result = $conn->query($sql);

        $emails = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $emails[] = $row["email"];
                $email = $row["email"]; 
        $subject = "New Event: " . $event->eventName;
        $body = "Hello ,<br><br>A new event titled <strong>{$event->eventName}</strong> has been added.<br>Location: {$event->location}<br>Date: {$event->date}<br><br>Log in to enroll.";
        sendEmail($email, $subject, $body);
            }
        } else {
            echo "No donors found.";
        }

        
    }

}

?>