<?php

class layout
{
    public function header($conf)
    {
        ?>
         <header>
            <h1>Welcome to <?php print $conf['site_name']; ?></h1>
        </header>
        <?php
    }

    public function footer($conf)
    {
        ?>
        <footer>
            <p>Copyright &copy; <?php echo date("Y"); ?> <?php print $conf['site_name']; ?> - All Rights Reserved</p>
        </footer>
        <?php
    }
}
?>