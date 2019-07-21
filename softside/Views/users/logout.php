        <?php
            if(empty($_SESSION)){
                header("Location: " . URL . 'users' . DS . 'index' . DS);
                die();
            }
        ?>