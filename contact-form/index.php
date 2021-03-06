<?php
require_once './vendor/autoload.php';

$helperLoader = new SplClassLoader('Helpers', './vendor');
$mailLoader   = new SplClassLoader('SimpleMail', './vendor');

$helperLoader->register();
$mailLoader->register();

use Helpers\Config;
use SimpleMail\SimpleMail;

$config = new Config;
$config->load('./config/config.php');
$subject = "Secure Message";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['form-message'])) {
    $name    = stripslashes(trim($_GET['form-message']));
    $pattern = '/[\r\n]|Content-Type:|Bcc:|Cc:/i';

    if (preg_match($pattern, $name)) {
        die("Header injection detected");
    }

    if ($name) {
        $emailSent = true;
    } else {
        $hasError = true;
    }
}
?><!DOCTYPE html>
<html>
<head>
    <title>The Whistleblower</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="css/secret.css">
</head>
<body>
    <div class="jumbotron">
        <div class="container banner">
            <h1 class="banner">Report any illegal or illicit activities anonymously.</h1>
            <p>*Powered by Equifacts*</p>
        </div>
        <iframe src="https://evil-third-party.herokuapp.com/"></iframe>
    </div>
    <?php if(!empty($emailSent)): ?>
        <div class="col-md-6 col-md-offset-3">
            <div class="alert alert-success text-center"><?php echo $config->get('messages.success'); ?></div>
        </div>
    <?php endif; ?>
    <?php if(!empty($hasError)): ?>
        <div class="col-md-5 col-md-offset-4">
            <div class="alert alert-danger text-center"><?php echo $config->get('messages.error'); ?></div>
        </div>
    <?php endif; ?>

    <div class="col-md-6 col-md-offset-3">
        <p style="float:left">Submit an anonymous tip. To protect your privacy, we ask that no personally identifiable information be included in your message.</p>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="application/x-www-form-urlencoded" id="secure-form" class="form-horizontal" method="GET">
            <div class="form-group">
                <div class="col-lg-10">
                    <textarea rows="3" class="form-control" id="form-message" name="form-message" placeholder="<?php echo $config->get('fields.name'); ?>" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10">
                    <button type="submit" class="btn btn-default" style="float: right"><?php echo $config->get('fields.btn-send'); ?></button>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="public/js/contact-form.js"></script>
    <script type="text/javascript">
        new ContactForm('#secure-form');
    </script>
    
</body>
</html>
