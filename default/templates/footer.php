<?php 
//Data object so the data will always up to actual date on footer copyright section.
$copyright = new DateTime(); ?>
</main>
<footer>
    &copy; Kickoff <?=$copyright->format('Y');?>
</footer>
</body>
</html>
