<?php

require_once '../../database.php';


$sql = "SELECT advertisement_url, current_price FROM advertisements";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $link = $row["advertisement_url"];
        $previous_price = $row["current_price"];

        $curl = curl_init($link);
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
        $current_price = 0;

        if ($elements->length > 0) {
            foreach ($elements as $element) {
                $price = $element->textContent;
                $price = preg_replace("/[^0-9]/", "", $price);
                $current_price = intval($price);
            }
        }

        if ($previous_price != $current_price) {
            $update_sql = "UPDATE advertisements SET current_price = $current_price WHERE advertisement_url = '$link'";
            if ($conn->query($update_sql) === TRUE) {
                
                $sql2 = "SELECT email FROM subscriptions WHERE advertisement_url = $link";
                $result2 = $conn->query($sql2);
                while($row2 = $result2->fetch_assoc()){
                    $to = $row2["email"];
                    $subject = "Изменения цены на товар";
                    $message = "Цена на товар - $link изменилась с $previous_price на $current_price";
                    $headers = "From: koval@pandatestkoval.zzz.com.ua";

                    mail($to, $subject, $message, $headers);
                }
            } else {
                echo "Ошибка при обновлении цены: " . $conn->error;
            }
        } else {
            echo "Цена не изменилась для ссылки: $link<br>";
        }
    }
} else {
    echo "0 результатов";
}



?>