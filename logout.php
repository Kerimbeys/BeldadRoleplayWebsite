<?php
/**
 * MTA:SA UCP - Çıkış İşlemi
 */

require_once 'session.php';

logout();

header('Location: login.php?message=logged_out');
exit();

