<?php
$ch = curl_init("https://www.google.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "❌ cURL HATASI: " . curl_error($ch);
} else {
    echo "✅ cURL çalışıyor! Google'dan cevap geldi: " . strlen($response) . " karakter.";
}
curl_close($ch);
