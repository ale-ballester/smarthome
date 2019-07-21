        <?php
            $display = "hidden";
            switch ($data) {
                case 1:
                    $display = "";
                    $msg = "Success, now log in.";
                    break;
                case 2:
                    $msg = "Username already taken.";
                    break;
                case 3:
                    $msg = "Passwords do not match";
                    break;
            }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-5 col-md-3">
                    <div class="form-login">
                    <h4>Register</h4>
                    <form class="form-horizontal" action="" method="POST">
                        <input type="text" name="name" class="form-control input-sm chat-input" placeholder="complete name" required />
                        <input type="email" name="email" class="form-control input-sm chat-input" placeholder="email" required />
                        <input type="text" name="username" class="form-control input-sm chat-input" placeholder="username" required />
                        <input type="password" name="pass1" class="form-control input-sm chat-input" placeholder="password" required />
                        <input type="password" name="pass2" class="form-control input-sm chat-input" placeholder="verify password" required />
                        <div class="wrapper">
                        <span class="group-btn">     
                            <button class="btn btn-info" type="submit">Register</button>
                            <button class="btn btn-warning" type="reset">Reset</button>
                            <a href="<?php echo URL; ?>users/index/" class="btn btn-danger">Cancel</a>
                            <br />
                            <div <?php echo $display; ?>><a href="<?php echo URL; ?>users/index/" class="btn btn-primary">Log in</a></div>
                        </span>
                        <?php echo $msg; ?>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>