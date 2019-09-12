<?php

class InputHelper {
    
    public static function getBodyJson() {
        $input = file_get_contents("php://input");
        return json_decode($input);
    }
    
}
