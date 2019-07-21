        <?php
            $msg = "";
            switch ($data) {
                case 1:
                    $msg = "Passwords do not match";
                    break;
                case 2:
                    $msg = "Success";
                    break;
                case 3:
                    $msg = "Old password is wrong";
                    break;
            }
        ?>
        <h3>Settings</h3>
        <form class="form-horizontal" action="" method="POST">
            <input type="password" name="oldpass" class="form-control input-sm chat-input" placeholder="old password" required />
            <input type="password" name="newpass1" class="form-control input-sm chat-input" placeholder="new password" required />
            <input type="password" name="newpass2" class="form-control input-sm chat-input" placeholder="enter new password again" required />
            <div class="wrapper">
                <span class="group-btn">     
                    <button class="btn btn-success" type="submit">Change password</button>
                </span>
            </div>
            <?php echo $msg; ?>
        </form>
        <a href="<?php echo URL; ?>users/control/" class="btn btn-danger">Cancel</a>