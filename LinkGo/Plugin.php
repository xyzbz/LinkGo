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

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 无需白名单配置
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form) {}

public static function convertLinks($content, $widget, $lastResult)
{
    $content = empty($lastResult) ? $content : $lastResult;
    $siteUrl = Typecho_Widget::widget('Widget_Options')->siteUrl;

    return preg_replace_callback(
        '/<a\s+(?:[^>]*?\s+)?href=("|\')([^"\']+)\1([^>]*)>(.*?)<\/a>/is', // 支持嵌套 HTML 结构
        function ($matches) use ($siteUrl) {
            $url = $matches[2]; // 捕获 href 属性的值
            $attributes = $matches[1] . $matches[3]; // 捕获其他属性
            $innerContent = $matches[4]; // 捕获 <a> 标签的内部内容
            // 检查链接是否是本站链接
            if (strpos($url, $siteUrl) === false) {
                $encodedUrl = base64_encode($url);
                // 强制添加 target="_blank" 属性
                if (strpos($attributes, 'target=') === false) {
                    $attributes .= ' target="_blank"';
                }
                return '<a href="' . $siteUrl . 'go?target=' . $encodedUrl . '"' . $attributes . '>' . $innerContent . '</a>';
            } else {
                return '<a href="' . $url . '"' . $attributes . '>' . $innerContent . '</a>';
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
            '/<\s*a\s+(?:[^>]*?\s+)?href="([^"]*)"(.*?)>/i', // 支持复杂 <a> 标签
            function ($matches) use ($siteUrl) {
                $url = $matches[1];
                $attributes = $matches[2];
                // 检查链接是否是本站链接
                if (strpos($url, $siteUrl) === false) {
                    $encodedUrl = base64_encode($url);
                    // 强制添加 target="_blank" 属性
                    if (strpos($attributes, 'target=') === false) {
                        $attributes .= ' target="_blank"';
                    }
                    return '<a href="' . $siteUrl . 'go?target=' . $encodedUrl . '"' . $attributes . '>';
                } else {
                    return '<a href="' . $url . '"' . $attributes . '>';
                }
            },
            $content
        );
    }

    public static function convertAuthorUrl($comment, $widget)
    {
        $siteUrl = Typecho_Widget::widget('Widget_Options')->siteUrl;
        $url = $comment['url'];

        // 检查链接是否是本站链接
        if (strpos($url, $siteUrl) === false && !empty($url)) {
            $encodedUrl = base64_encode($url);
            $comment['url'] = $siteUrl . 'go?target=' . $encodedUrl . '" target="_blank';
        }
        return $comment;
    }
}
