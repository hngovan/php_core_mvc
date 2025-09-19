<h1><?= $statusCode ?>!</h1>
<?php
/** @var $exception \Exception */
?>
<p><?php echo $exception->getCode() ?> - <?php echo $exception->getMessage() ?></p>
