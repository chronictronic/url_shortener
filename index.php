<?php
include 'Shortener.php';
$shortener = new Shortener();
    if(!empty($_GET['t'])){
        $link = $shortener->getOriginalUrl($_GET['t']);
        if($link) { header('Location:' .$link); }
        else { header('Location: u3086.indigo.elastictech.org/'); }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <style type="text/css">
            * { margin: 0; padding: 0; }
            html, body { height: 100%; width: 100%;}
            input[type=text] { height: 40px; width: 300px; border-radius: 2px; border: 1px solid gray;
                               font-size: 15pt; 
            }
            input[type=submit] { height: 42px; width: 150px; font-size: 12pt;}
            div.flex { display: flex; }
                .container { position: fixed; width: 100%; height: 100%; overflow: auto;
                             flex-direction: column; align-items: center; align-content: center; justify-content: center; }
                .content { margin: auto; }
            div.link-group {margin-top: 10px;}
                a#short-url { font-size: 18pt; font-family: sans-serif;}
        </style>
    </head>
    <body>
        <div class="flex container">
            <form action="http://u3086.indigo.elastictech.org/Handler.php" method="POST" id="form">
                <input type="text" name="url">
                <input type="submit" value="Получить ссылку">
            </form>
            <div class="link-group"><a href="" id="short-url"></a><span>&nbsp;</span></div>
        </div>        
        <script type="text/javascript">
            $('document').ready(function(){
                $('#form').submit(function(e){
                    e.preventDefault();
                    var $form = $(this);
                    $.ajax({
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        data: $form.serialize(),
                        success: function(data){
                            $('#short-url').text(data);
                            $('#short-url').prop('href', data);
                            $('#short-url').scroll();
                        }
                    });                 
                });

        });
        </script>
    </body>
</html>
