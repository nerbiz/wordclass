<?php
// Indentation matches <head> inside <html>
echo '
        <!-- Global Site Tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=' . $trackingCode . '"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag("js", new Date());
            gtag("config", "' . $trackingCode . '");
        </script>
';
