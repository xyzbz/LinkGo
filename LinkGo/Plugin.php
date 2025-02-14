<?php
/**
 * 外部链接自动跳转插件
 * 
 * @package LinkGo
 * @author 陶小桃Blog、子夜松声、DeepSeek人工智能
 * @version 1.0
 * @link https://xyzbz.cn/archives/1325/
 */
class LinkGo_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('LinkGo_Plugin', 'convertLinks');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('LinkGo_Plugin', 'convertLinks');
        Typecho_Plugin::factory('Widget_Abstract_Comments')->contentEx = array('LinkGo_Plugin', 'convertCommentLinks');
        Typecho_Plugin::factory('Widget_Abstract_Comments')->filter = array('LinkGo_Plugin', 'convertAuthorUrl');
        return '插件已激活';
    }

    public static function deactivate()
    {
        return '插件已禁用';
    }

    public static function config(Typecho_Widget_Helper_Form $form) {}

    public static function personalConfig(Typecho_Widget_Helper_Form $form) {}

    public static function convertLinks($content, $widget, $lastResult)
    {
        $content = empty($lastResult) ? $content : $lastResult;
        $siteUrl = Typecho_Widget::widget('Widget_Options')->siteUrl;
        return preg_replace_callback(
            '/<\s*a\s+(.*?)href=([\'"])([^\'"]+)\2(.*?)>/i',
            function ($matches) use ($siteUrl) {
                $url = $matches[3];
                if (strpos($url, $siteUrl) === false) {
                    $encodedUrl = base64_encode($url);
                    return '<a ' . $matches[1] . 'href="' . $siteUrl . 'go?target=' . $encodedUrl . '" target="_blank" ' . $matches[4] . '>';
                } else {
                    return '<a ' . $matches[1] . 'href="' . $url . '" ' . $matches[4] . '>';
                }
            },
            $content
        );
    }

    public static function convertCommentLinks($content, $widget, $lastResult)
    {
        $content = empty($lastResult) ? $content : $lastResult;
        $siteUrl = Typecho_Widget::widget('Widget_Options')->siteUrl;
        return preg_replace_callback(
            '/<\s*a\s+(.*?)href=([\'"])([^\'"]+)\2(.*?)>/i',
            function ($matches) use ($siteUrl) {
                $url = $matches[3];
                if (strpos($url, $siteUrl) === false) {
                    $encodedUrl = base64_encode($url);
                    return '<a ' . $matches[1] . 'href="' . $siteUrl . 'go?target=' . $encodedUrl . '" target="_blank" ' . $matches[4] . '>';
                } else {
                    return '<a ' . $matches[1] . 'href="' . $url . '" ' . $matches[4] . '>';
                }
            },
            $content
        );
    }

    public static function convertAuthorUrl($comment, $widget)
    {
        $siteUrl = Typecho_Widget::widget('Widget_Options')->siteUrl;
        $url = $comment['url'];
        if (strpos($url, $siteUrl) === false && !empty($url)) {
            $encodedUrl = base64_encode($url);
            $comment['url'] = $siteUrl . 'go?target=' . $encodedUrl . '" target="_blank';
        }
        return $comment;
    }
}
