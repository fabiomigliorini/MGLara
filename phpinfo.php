<?php
$dateTime = new DateTime();
$dateTime->setTimeZone(new DateTimeZone('America/Cuiaba'));
echo $dateTime->format('d/m/Y H:i:s e P');
//var_dump($dateTime);

echo phpinfo();
?>
