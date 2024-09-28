<?php

class UtilModel extends CI_Model {
    public function validatePourcentages($pourcentage) {
        $total = 0;
        foreach ($pourcentage as $p) {
            if ($p < 0 || $p > 100) {
                return false;
            }
            $total += $p;
        }
        return ($total == 100);
    }
}

?>