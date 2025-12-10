<?php

session_start();
session_destroy();
session_abort();

$newURL = "../Phase3/login.html";
header("Location: " . $newURL);