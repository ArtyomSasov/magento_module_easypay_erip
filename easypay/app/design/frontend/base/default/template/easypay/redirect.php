<?php header("Content-Type: text/html; charset=UTF-8");?>
<html>
    <body>
        <?php echo $this->__('Вы будете перенаправлены на сайт EasyPay в течение нескольких секунд.'); ?>
        <?php echo $this->getForm()->toHtml(); ?>
        <script type="text/javascript">document.getElementById("easypay_redirect").submit();</script>
    </body>
</html>