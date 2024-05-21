<?php
if ($_SESSION['user']['type_user'] !== 'admin') {
    
    echo "You do not have permission.";
    exit;
}
?>