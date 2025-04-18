<?php

class Util {
    public function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);

        return $data;
    }

    public function showMessage($type, $message) {
        return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                <strong>' . $message . '</strong>
              </div>';
    }
}

?>