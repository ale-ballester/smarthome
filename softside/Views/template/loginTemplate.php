<?php namespace Views;

    $template = new Template();

    class Template{
        
        public function __construct(){
            
?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Log In</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" />
            <link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/general.css" type="text/css" />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        </head>
        <body>
<?php
        }
        
        public function __destruct(){
?>
        </body>
    </html>
<?php
        }
    }

?>