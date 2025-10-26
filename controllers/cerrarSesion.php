<?php
session_start();
session_unset();
session_destroy();

header("Content-Type: application/json; charset=utf-8");
echo json_encode([
  "status" => "success",
<<<<<<< HEAD
  "message" => "sesion cerrada correctamente."
=======
  "message" => "Sesión cerrada correctamente."
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
]);
exit;
?>
