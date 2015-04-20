<?php

/*
 * start out php session
 * coins entered into the machine are stored in a session before the sale of a product
 */
session_start();

/**
 * set the correct timezone
 */
date_default_timezone_set("GMT");

/**
 * define our mysql credentials
 */
define('VENDING_MYSQL_HOST','localhost');
define('VENDING_MYSQL_DB','vending');
define('VENDING_MYSQL_USERNAME','user');
define('VENDING_MYSQL_PASSWORD','password');

/**
 * set the currency symbol
 */
define('VENDING_CURRENCY_CODE','&pound;');
