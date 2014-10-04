<?php

//autoloading classes
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});



try {
	$session = new Client();
} catch (Exception $e) {
	echo $e->getMessage(), "\n";
}

$session->initialize("00i0nm0dk1gifr9xmcveeh9c5ts1fsio", "00i0nm0dk1euwdbocxy6wdebrrstrtj8");

?>