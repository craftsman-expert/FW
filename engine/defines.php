<?php


define('DS', DIRECTORY_SEPARATOR);
define('DOCUMENT_ROOT', dirname($_SERVER["DOCUMENT_ROOT"], 1) . DS . $_SERVER['HTTP_HOST']);
define('ROOT_DIR', dirname(__DIR__, 1));


/**
 * Системные коды ошибок
 */

/** Произошла неизвестная ошибка. */
const UNKNOWN_ERROR = 1000;
/** Языковый пакет не найден */
const LANGUAGE_PACKAGE_NOT_FOUND = 1001;
/** Языковый пакет поврежден */
const LANGUAGE_PACKAGE_DAMAGED = 1002;
/** Передан неизвестный метод. */
const UNKNOWN_METHOD = 3;




/**
 * Пользовательские коды ошибок
 */

/** Авторизация пользователя не удалась.  */
const ERR_USER_AUTHORIZATION_FAILED = 5;

/** Доступ запрещён.  */
const ERR_USER_ACCESS_DENIED = 15;

/** Авторизация временно недоступна */
const ERR_AUTHORIZATION_TEMPORARILY_UNAVAILABLE = 16;

/** Требуется авторизация пользователя.  */
const USER_AUTHORIZATION_REQUIRED = 17;
/** Пользователь уже зарегистрирован  */
const USER_ALREADY_EXISTS = 18;

/** Некорректный номер телефона */
const INVALID_PHONE_NUMBER = 30;
