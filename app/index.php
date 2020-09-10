<?php
/**
 * app/index.php
 *
 * アプリケーション読み込み
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @version     3.0
**/

/**
 * 基本の定数と関数の読み込み
**/
require('Config/bootstrap.php');

/**
 * アプリケーションの Base
**/
autoload(APP_DIR . DS . 'Base');

/**
 * アプリケーションの Core
**/
autoload(APP_DIR . DS . 'Core');

/**
 * アプリケーションの View
**/
autoload(APP_DIR . DS . 'View');

/**
 * アプリケーション
**/
require(APP_DIR . DS . 'app.php');
