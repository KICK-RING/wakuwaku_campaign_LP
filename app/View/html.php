<?php
/**
 * app/View/html.php
 *
 * HTML表示用の関数
 *
 * @copyright   Copyright (c) 2015 Sinciate Inc.
 * @link        https://sinciate.co.jp
 * @since       3.0
**/

//$themeDir     = get_stylesheet_directory();
//$themeDirUrl  = get_template_directory_uri();
//$assetDirName = 'asset';
//$imageDirName = 'img';
//$cssDirName   = 'css';
//$jsDirName    = 'js';


/**
 * image()
 * 画像のURLをハッシュ付きで取得（キャッシュチェック）
 *
 * @param str | image name
 * @return URL
 **/
function image($name)
{
    $url  = sprintf('%s/asset/img/%s', get_template_directory_uri(), $name);
    $path = sprintf('%s/asset/img/%s', THEME_DIR, $name);

    if (file_exists($path)) {
        $url .= '?' . filemtime($path);
    }

    return $url;
}

/**
 * css()
 * 画像のURLをハッシュ付きで取得（キャッシュチェック）
 *
 * @param str | image name
 * @return URL
 **/
function css($name)
{
    $url  = sprintf('%s/asset/css/%s.min.css', get_template_directory_uri(), $name);
    $path = sprintf('%s/asset/css/%s.min.css', THEME_DIR, $name);
    //dd($url);

    if (file_exists($path)) {
        $url .= '?' . filemtime($path);
    }

    return $url;
}

/**
 * js()
 * 画像のURLをハッシュ付きで取得（キャッシュチェック）
 *
 * @param str | image name
 * @return URL
 **/
function js($name)
{
    $url  = sprintf('%s/asset/js/%s.min.js', get_template_directory_uri(), $name);
    $path = sprintf('%s/asset/js/%s.min.js', THEME_DIR, $name);

    if (file_exists($path)) {
        $url .= '?' . filemtime($path);
    }

    return $url;
}
