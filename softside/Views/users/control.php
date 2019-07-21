        <script type="text/javascript">
            function act(dev){
                $(document).ready(function(){
                    $.post("<?php echo URL . 'users' . DS . 'act' . DS; ?>",
                    {
                        device: dev
                    }, function(data, status){
                    });
                });
            }
        </script>
        <script type="text/x-script.multithreaded-js">
            function sync() {
                $(document).ready(function(){
                    $.post("<?php echo URL . 'users' . DS . 'sync' . DS; ?>", {},
                        function(data,status){
                            var devices = data.split("\\");
                            var rem = devices.splice(-1, 1);
                            for (var i = 0;i<devices.length;i++) {
                                var deval = devices[i].split("=");
                                var devchar = deval[0].split(".");
                                var devid = devchar[0];
                                var type = devchar[1];
                                var value = deval[1];
                                var statid = devid.concat("Status");
                                if(type == "digitalout"){
                                    if(value == "255"){
                                        document.getElementById(statid).innerHTML = value;
                                        document.getElementById(devid).className = "btn btn-success";
                                        document.getElementById(devid).innerHTML = "ON";
                                    }
                                    if(value == "0"){
                                        document.getElementById(statid).innerHTML = value;
                                        document.getElementById(devid).className = "btn btn-danger";
                                        document.getElementById(devid).innerHTML = "OFF";
                                    }    
                                } else if (type == "analogout"){
                                    
                                } else{
                                    document.getElementById(statid).innerHTML = value;    
                                }
                            }
                        });
                    });
                }

            setInterval(sync, 500);
        </script>
        <div class="btn-group btn-group-justified main-btns main-btns">
            <a href="<?php echo URL; ?>users/control/" class="btn btn-success">Home</a>
            <a href="<?php echo URL; ?>users/adddev/" class="btn btn-warning">Add device</a>
            <a href="<?php echo URL; ?>users/settings/" class="btn btn-info">Settings</a>
            <a href="<?php echo URL; ?>users/logout/" class="btn btn-danger">Log out</a>
        </div>
        <br />
        <br />
        <table class="table table-hover devs" width="100%">
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $num = sizeof($data['id']);
                    for($i = 0; $i < $num; $i++){
                ?>
                <tr>
                    <td><?php echo $data['name'][$i]; ?></td>
                    <td id="<?php echo $data["id"][$i]; ?>Status"><?php echo $data['obj'][$i]->getStatus(); ?></td>
                    <td>
                        <?php
                            if($data['type'][$i] == "digitalout"){
                                if($data['obj'][$i]->getStatus() == 0){
                                    echo '<button id="' . $data['id'][$i] . '" type="button" class="btn btn-danger" onclick="act(\'' . $data['id'][$i] . '\')">OFF</button>';
                                } else{
                                    echo '<button id="' . $data['id'][$i] . '" type="button" class="btn btn-success" onclick="act(\'' . $data['id'][$i] . '\')">ON</button>';
                                }
                            } elseif ($data['type'][$i] == "analogout") {
                                echo '<input id="' . $data["id"][$i] . '" type="range" min="0" max="100"></input>';
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>