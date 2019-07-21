        <div class="container">
            <div class="row">
                <div class="col-md-offset-5 col-md-3">
                    <div class="form-login">
                    <h4>New device</h4>
                    <form class="form-horizontal" action="" method="POST">
                        <input type="text" name="id" class="form-control input-sm chat-input" placeholder="ID" required />
                        <input type="text" name="code" class="form-control input-sm chat-input" placeholder="code" required />
                        <input type="text" name="name" class="form-control input-sm chat-input" placeholder="name" required />
                        <div class="wrapper">
                        <span class="group-btn">     
                            <button class="btn btn-info" type="submit">Add device</button>
                            <button class="btn btn-warning" type="reset">Reset</button>
                            <a href="<?php echo URL; ?>users/control/" class="btn btn-danger">Cancel</a>
                        </span>
                        <?php echo $data ?>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>