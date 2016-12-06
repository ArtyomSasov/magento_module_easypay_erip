<?php header("Content-Type: text/html; charset=UTF-8");?>
<html>
    <body>
        <?php echo $this->getForm()->toHtml(); ?>
        <script type="text/javascript">document.getElementById("erip_redirect").submit();</script>
    </body>
</html>