<?php
$rules = [];

$rules['get'] = require 'get.php';
$rules['post'] = require 'post.php';
$rules['cookie'] = require 'cookie.php';
$rules['ua'] = require 'user-agent.php';