<?php

require_once '../../database.php';

class Scraper {
    private $link;
    private $conn;

    public function __construct($link, $conn) {
        $this->link = $link;
        $this->conn = $conn;
    }

    public function scrape() {
        $curl = curl_init($this->link);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            die('Ошибка cURL: ' . curl_error($curl));
        }

        curl_close($curl);

        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($response);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $elements = $xpath->query('//h3[@class="css-12vqlj3"]');

        if ($elements->length > 0) {
            foreach ($elements as $element) {
                $price = preg_replace("/[^0-9]/", "", $element->textContent);
                $price = intval($price);

                $this->insertSubscription($this->link, $_POST['email']);
                $this->insertAdvertisement($this->link, $price);
            }
        }
    }

    private function insertSubscription($link, $email) {
        $check_sql = "SELECT email_verify FROM subscriptions WHERE email = ?";
        $check_stmt = $this->conn->prepare($check_sql);
        $check_stmt->execute([$email]);
        $existing_email = $check_stmt->fetch();
        $check_stmt->close();
    
        try{
            if (!$existing_email) {
                $insert_sql = "INSERT INTO subscriptions (advertisement_url, email, email_verify) VALUES (?, ?, false)";
            } else {
                $insert_sql = "INSERT INTO subscriptions (advertisement_url, email, email_verify) VALUES (?, ?, true)";
            }
    
            $insert_stmt = $this->conn->prepare($insert_sql);
            $insert_stmt->execute([$link, $email]);
            
            echo "Вы успешно подписались на объявление.";
        }catch (Exception $e) {
            echo "Ошибка при подписке, попробуйте позже.";
        }
    }
    

    private function insertAdvertisement($link, $price) {
        $sql = "INSERT INTO advertisements (advertisement_url, current_price) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$link, $price]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $link = $_POST['link'] ?? '';

    $scraper = new Scraper($link, $conn);
    $scraper->scrape();
}
?>
