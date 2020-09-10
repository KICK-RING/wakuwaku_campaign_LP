<?php
/**
 * app/Config/bootstrap.php
 *
 * 定数の設定
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       1.0
 * @version     3.0
**/

/**
 * ディレクトリーセパレーター
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * テーマのディレクトリ
 */
define('THEME_DIR', get_template_directory());

/**
 * アプリケーションのディレクトリ
 */
define('APP_DIR', THEME_DIR . DS . 'app');

/**
 * 拡張ライブラリのディレクトリ
 */
define('LIB_DIR', THEME_DIR . DS . 'lib');

/**
 * dd()
 * デバッグ用の関数
 *
 * @param mixed
 * @return mixed
 **/
function dd($value)
{
    $files = debug_backtrace();

    echo '<pre>';
    echo '//--- File ---//' . '<br>';
    echo $files[0]['file'] . '<br>';
    echo '//--- Line ---//' . '<br>';
    echo $files[0]['line'] . '<br>';
    var_dump($value);
    echo '</pre>';
    exit;
}

/**
 * autoload()
 * オートロード用の関数
 *
 * @param string
 **/
function autoload($dir)
{
    $files = glob($dir . '/*.php');

    foreach ($files as $file) {
        require($file);
    }
}
