<?php
$tenant = $_GET['tenant'] ?? '';
$email = $_GET['email'] ?? '';
$password = $_GET['password'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User Notification Pusher.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <main class="content">
        <div class="container-off px-3">
            <h2>User Notification Pusher.com</h2>
            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-right">

                        <div class="px-4 mb-5">
                            <form action="" method="get">

                                <div class="form-group">
                                    <label>Tenant</label>
                                    <input type="text" name="tenant" class="form-control  mb-2" placeholder="Tenant" value="<?= $tenant ?>">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control  mb-2" placeholder="Email" value="<?= $email ?>">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" name="password" class="form-control  mb-2" placeholder="Password" value="<?= $password ?>">
                                </div>

                                <button type="submit" class="btn btn-primary">Apply</button>
                            </form>

                        </div>


                    </div>
                    <div class="col-12 col-lg-7 col-xl-9 pt-4">

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                Notifications
                                <span class="badge badge-pill badge-danger notification-count">0</span>
                            </button>
                            <div class="dropdown-menu notification-list">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <style type="text/css">
        body {
            margin-top: 20px;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js" integrity="sha512-QTnb9BQkG4fBYIt9JGvYmxPpd6TBeKp6lsUrtiVQsrJ9sb33Bn9s0wMQO9qVBFbPX3xHRAsBHvXlcsrnJjExjg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        axios.get('/sanctum/csrf-cookie')
            .then(response => {
                axios.post('/admin/auth/login', {
                        'login': '<?= $email ?>',
                        'password': '<?= $password ?>'
                    }, {
                        'headers': {
                            'Tenant': '<?= $tenant ?>'
                        }
                    })
                    .then(response => {
                        axios.get('/admin/auth/user', {
                                'headers': {
                                    'Tenant': '<?= $tenant ?>'
                                }
                            })
                            .then(response => {
                                /****************************** */
                                $('.notification-count').html(response.data.data.notifications.count);

                                $.each(response.data.data.notifications.list, function(i, item) {
                                    $('.notification-list').prepend('<a class="dropdown-item" href="javascript:;">' + item.data.subject + ' <br><small>(' + item.created_at + ')</small></a>');
                                });

                                /*********************** */

                                Pusher.logToConsole = true;

                                var pusher = new Pusher('634f4d0e0d42805e948d', {
                                    'cluster': 'ap2',
                                    'channelAuthorization': {
                                        'endpoint': '/broadcasting/auth',
                                        'headers': {
                                            'Tenant': '<?= $tenant ?>'
                                        },
                                    },
                                });

                                var channel = pusher.subscribe('private-usernotifications.' + response.data.data.id);

                                channel.bind('notification', function(data) {
                                    $('.notification-count').html(data.count);

                                    $('.notification-list').prepend('<a class="dropdown-item" href="javascript:;">' + data.notification.data.subject + ' <br><small>(' + data.notification.created_at + ')</small></a>');

                                });
                                /*********************** */
                            })
                            .catch(function(error) {
                                console.error(error);
                            });


                    })
                    .catch(function(error) {
                        console.error(error);
                    });

            })
            .catch(error => {
                alert(error);
            });

        // axios.get('/admin/auth/user', {
        //         'headers': {
        //             "Authorization": "Bearer  <?= $token ?>",
        //         }
        //     })
        //     .then(response => {
        //         /*********************** */
        //         console.log(response);
        //         /*********************** */
        //     })
        //     .catch(function(error) {
        //         console.error(error);
        //     });


        // Pusher.logToConsole = true;

        // var pusher = new Pusher('634f4d0e0d42805e948d', {
        //     cluster: 'ap2',
        //     channelAuthorization: {
        //         endpoint: "/broadcasting/auth",
        //         headers: {
        //             "Authorization": "Bearer  <?= $token ?>",
        //         },
        //     },
        // });


        /*************** */
        // var channel = pusher.subscribe('private-usernotifications.<?= $customer_id ?>');

        // var myUser = 0;
        // channel.bind('notification', function(data) {
        //     console.log(data);
        // });

        /************** */
        var axiosHeader = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer <?= $token ?>',
            'Tenant': '<?= $tenant ?>'
        };
        /************** */
        // Chat history loading request: /admin/communication/chat-message-history
        // axios.post('/admin/communication/chat-message-history', {
        //         'customer_id': '<?= $customer_id ?>'
        //     }, {
        //         headers: axiosHeader
        //     })
        //     .then((response) => {
        //         myUser = response.data.user;
        //         var chatMessages = $(".chat-messages");
        //         chatMessages.html('');
        //         $.each(response.data.message, function(i, item) {
        //             chatMessages.append(chatMessageBody(item));
        //             scrollBottom();
        //         });
        //         $(".chat-notifications").html(response.data.unread);

        //         /********* */
        //         var channel_notify = pusher.subscribe('private-chatnotification.' + myUser);
        //         channel_notify.bind('chat_notification', function(data) {
        //             $(".chat-notifications").html(data.total);
        //         });
        //         /********* */
        //     })
        //     .catch(error => {
        //         alert(error.response.data.message);
        //     });
        /************** */

        /*************** */
    </script>
</body>

</html>
