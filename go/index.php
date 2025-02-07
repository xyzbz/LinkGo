<?php
// 安全性检查
if (strlen($_SERVER['REQUEST_URI']) > 255 ||
    strpos($_SERVER['REQUEST_URI'], "eval(") ||
    strpos($_SERVER['REQUEST_URI'], "base64")) {
    @header("HTTP/1.1 414 Request-URI Too Long");
    @header("Status: 414 Request-URI Too Long");
    @header("Connection: Close");
    @exit;
}

// 获取 Base64 编码的目标链接
$encodedTarget = $_GET['target'] ?? '';

// 解码目标链接
$target = base64_decode($encodedTarget);

// 处理目标链接
if (!empty($target) && filter_var($target, FILTER_VALIDATE_URL)) {
    $parsed_url = parse_url($target);
    if (in_array($parsed_url['scheme'], ['http', 'https'])) {
        $url = $target;
        $title = '安全中心 - 页面跳转';
    } else {
        $url = 'http://' . $_SERVER['HTTP_HOST'];
        $title = '非法协议，正在返回首页...';
    }
} else {
    $title = '非法 URL，正在返回首页...';
    $url = 'http://' . $_SERVER['HTTP_HOST'];
}

// 提取目标域名
$parsed_url = parse_url($url);
$display_url = $parsed_url['host'] ?? $url;

// 动态内容
$siteUrl = 'http://' . $_SERVER['HTTP_HOST']; // 站点 URL
$siteTitle = '您的网站名称'; // 站点标题
$logoUrl = 'https://your-site.com/logo.png'; // Logo 图片 URL
$siteCreatedYear = date('Y'); // 建站年份
$currentYear = date('Y'); // 当前年份
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="robots" content="noindex, nofollow" />
    <title><?php echo $title; ?> - 跳转提示</title>
    <style>
        body {
            font-family: 'Cascadia Code', 'Consolas', 'Microsoft YaHei', SimHei !important;
            margin: 0;
            padding: 0 30px;
            background: #fff;
            font-size: 12px
        }

        img {
            border: none
        }

        a {
            text-decoration: none;
            cursor: pointer;
            outline: 0
        }

        a:hover {
            text-decoration: underline
        }

        a,
        a:link,
        a:visited {
            color: #1e5494
        }

        a.btn_blue:focus {
            border-color: #93d4fc;
            box-shadow: 0 0 5px #60caff
        }

        a.btn_blue {
            display: inline-block;
            padding: 6px 25px;
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            border-radius: 3px;
            border: 1px solid #0d659b;
            color: #fff;
            color: #fff !important;
            background-color: #238aca;
            background: -moz-linear-gradient(top, #238aca, #0074bc);
            background: -webkit-linear-gradient(top, #238aca, #0074bc);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#238aca', endColorstr='#0074bc');
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#238aca', endColorstr='#0074bc')"
        }

        a.btn_blue:hover {
            text-decoration: none;
            background-color: #238aca;
            background: -moz-linear-gradient(top, #2a96d8, #0169a9);
            background: -webkit-linear-gradient(top, #2a96d8, #0169a9);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2a96d8', endColorstr='#0169a9');
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#2a96d8', endColorstr='#0169a9')"
        }

        a.btn_blue:active {
            background-color: #238aca;
            background: -moz-linear-gradient(top, #0074bc, #238aca);
            background: -webkit-linear-gradient(top, #0074bc, #238aca);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0074bc', endColorstr='#238aca');
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#0074bc', endColorstr='#238aca')";
            outline: 0
        }

        .remind_block {
            overflow: hidden
        }

        .remind_block .remind_content {
            overflow: hidden
        }

        .remind_block .remind_title {
            margin-bottom: 10px;
            padding-top: 3px;
            font-weight: 700;
            font-size: 20px;
            font-family: "Microsoft YaHei", "lucida Grande", Verdana
        }

        .remind_block .remind_detail {
            line-height: 1.5;
            font-size: 16px;
            color: #535353
        }

        .warning .remind_title {
            color: #16a085
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            padding-top: 25px
        }

        .header {
            margin-bottom: 5px
        }

        .footer {
            margin-top: 18px;
            text-align: center;
            color: #a0a0a0;
            font-size: 12px
        }

        .content {
            border: 1px solid #bbb;
            box-shadow: 0 0 3px #d4d4d4
        }

        .c-container {
            padding: 30px
        }

        .c-footer {
            padding: 10px 15px;
            background: #f1f1f1;
            border-top: 1px solid #bbb;
            overflow: hidden
        }

        .c-footer-a1,
        .c-footer-a2 {
            float: left
        }

        .c-footer-a2 {
            margin: 8px 0 0 15px
        }

        .safety-url {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dfdfdf;
            word-wrap: break-word;
            word-break: break-all
        }

        .footer .dot {
            margin: 0 .25em
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <div class="c-container warning">
                <div id="remind_block" class="remind_block" style="height: 260px;">
                    <div class="remind_content">
                        <div class="remind_title">
                            您将要访问
                        </div>
                        <div class="remind_detail">
                            <div class="safety-url">
                                <?php echo $url; ?>
                            </div>
                            <span style="color:#CC0000;font-weight:800;">温馨提示:</span><br />该网页不属于本站页面，我们无法确认该网页是否安全，它可能包含未知的安全隐患，请注意保护好个人信息！<br />页面将于5秒后自动跳转......
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="c-footer">
            <a href="<?php echo $url; ?>" rel="nofollow" class="c-footer-a1 btn_blue">继续访问</a>
            <a class="c-footer-a2" href="<?php echo $siteUrl; ?>" rel="nofollow">返回主页</a>
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = "<?php echo $url; ?>";
        }, 5000); // 5 秒后跳转
    </script>
</body>

</html>