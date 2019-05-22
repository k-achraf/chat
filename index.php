<?php
include 'User.php';
include 'Room.php';

$messages = Room::all();
session_start();

if (isset($_POST['submit'])){
    $user = new User();
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->submit();
}

$users = User::all();
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/style.css">

    <title>Web Chat</title>
</head>
<body>
<div class="overlay"></div>
<?php if (!isset($_SESSION['user']['id'])){ ?>

    <div class="login ">
        <h1>Login | Register</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
            <div class="emailbox">
                <input type="email" name="email" id="email">
                <span>email</span>
            </div>

            <div class="namebox">
                <input type="text" name="name" id="name">
                <span>name</span>
            </div>
            <button type="submit" name="submit" class="btn btn-sucsess">Submit</button>
        </form>
    </div>

<?php }else{ ?>
<div class="container">
    <div class="box">
        <div class="box-title text-center">
            <p>
                Chat Box
            </p>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="users-box">
                        <div class="user-details">
                            <input type="hidden" id="userId" value="<?php echo $_SESSION['user']['id'];?>">
                            <?php
                            echo '<b>'.$_SESSION['user']['name'].'</b>';
                            echo '<br/>';
                            echo '<small>'.$_SESSION['user']['email'].'</small>';
                            ?>
                        </div>
                        <hr>
                        <div class="users">
                            <h5>users</h5>
                            <?php
                            foreach ($users as $user){
                                ?>
                                <div class="user">
                                    <?php
                                    echo '<b>'.$user['name'].'</b>';
                                    echo '<br/>';
                                    echo '<small>'.$user['email'].'</small>';
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="messages">
                        <div class="messages-show">
                            <?php
                            foreach ($messages as $message){
                                if ($message['user_id'] == $_SESSION['user']['id']){
                                    $message['name'] = 'ME';
                                }
                                echo '<div class="msg"><b>'.$message['name'].'</b><small>'.$message['time'].'</small><p>'.$message['msg'].'</p></div>';
                            }
                            ?>
                        </div>

                        <div class="messages-send">
                            <form class="send-message">
                                <div class="form-group">
                                    <input class="form-control" name="message" id="msg" placeholder="message here..">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="assets/main.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");
        };
        conn.onmessage = function(e) {
            console.log(e.data);
            var data = JSON.parse(e.data);

            var row = '<div class="msg"><b>'+data.from+'</b><small>'+data.time+'</small><p>'+data.msg+'</p></div>';
            $('.messages-show').prepend(row);
        };

        $('.send-message').submit(function (e) {
            e.preventDefault();

            var msg         = $('#msg').val();
            var userId      = $('#userId').val();

            var data = {
                msg : msg,
                userId : userId
            };

            conn.send(JSON.stringify(data));
            $("#msg").val("");
        });
    });
</script>
</body>
</html>