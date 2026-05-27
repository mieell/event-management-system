<?php

// Vercel Serverless Function entrypoint
// Forwards all requests to CodeIgniter's main front controller

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

require __DIR__ . '/../public/index.php';
