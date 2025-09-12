<?php

class classes
{
    public function greet()
    {
        return "<h1> Hello ICS 2B!</h1>";
    }

    public function today()
    {
        return "<p>Today is" .date("l")."</p>";
    }
}


?>
