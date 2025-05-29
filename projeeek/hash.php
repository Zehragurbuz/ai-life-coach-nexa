<?php
// Güvenli şifreleme fonksiyonu

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
?>
