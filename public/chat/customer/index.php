<?php
$token = $_GET['token'] ?? '';
$tenant = $_GET['tenant'] ?? '';
$customer_id = $_GET['customer_id'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Customer Pusher.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <main class="content">
        <div class="container-off px-3">

            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-right">

                        <div class="px-4 mb-5">
                            <form action="" method="get">

                                <div class="form-group mt-3">
                                    <label>Authentication token</label>
                                    <input type="text" name="token" class="form-control mb-2" placeholder="Authentication token" value="<?= $token ?>">
                                </div>
                                <div class="form-group">
                                    <label>Tenant</label>
                                    <input type="text" name="tenant" class="form-control  mb-2" placeholder="Tenant" value="<?= $tenant ?>">
                                </div>
                                <div class="form-group">
                                    <label>Customer ID</label>
                                    <input type="number" name="customer_id" class="form-control  mb-2" placeholder="Customer ID" value="<?= $customer_id ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </form>

                            <h5 class="mt-5">Unread <span class="badge badge-pill badge-danger customer-chat-notifications">0</span></h5>

                        </div>


                    </div>
                    <div class="col-12 col-lg-7 col-xl-9">

                        <div class="position-relative">
                            <div class="chat-messages p-4" style="min-height: 500px;">
                                <div class="alert alert-warning" role="alert">
                                    Loading chat body ...
                                </div>
                            </div>
                        </div>

                        <div class="flex-grow-0 py-3 px-4 border-top">
                            <div class="input-group">
                                <input type="text" class="form-control user-message" placeholder="Type your message">
                                <button class="btn btn-primary action-send">Send</button>
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

        .chat-online {
            color: #34ce57
        }

        .chat-offline {
            color: #e4606d
        }

        .chat-messages {
            display: flex;
            flex-direction: column;
            max-height: 800px;
            overflow-y: scroll
        }

        .chat-message-left,
        .chat-message-right {
            display: flex;
            flex-shrink: 0
        }

        .chat-message-left {
            margin-right: auto
        }

        .chat-message-right {
            flex-direction: row-reverse;
            margin-left: auto
        }

        .py-3 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .px-4 {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }

        .flex-grow-0 {
            flex-grow: 0 !important;
        }

        .border-top {
            border-top: 1px solid #dee2e6 !important;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js" integrity="sha512-QTnb9BQkG4fBYIt9JGvYmxPpd6TBeKp6lsUrtiVQsrJ9sb33Bn9s0wMQO9qVBFbPX3xHRAsBHvXlcsrnJjExjg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('634f4d0e0d42805e948d', {
            cluster: 'ap2',
            channelAuthorization: {
                endpoint: "/customer/broadcasting/auth",
                headers: {
                    "Authorization": "Bearer  <?= $token ?>",
                    "Tenant": "<?= $tenant ?>"
                },
            },
        });

        /*************** */
        var channel = pusher.subscribe('private-chat.<?= $customer_id ?>');

        channel.bind('chat_message', function(data) {
            var chatMessages = $(".chat-messages");
            chatMessages.append(chatMessageBody(data.message));

            scrollBottom();
        });

        function chatMessageBody(data) {
            var date = new Date(data.created_at);
            var hoursAndMinutes = padTo2Digits(date.getHours()) + ':' + padTo2Digits(date.getMinutes());
            var yearMonthDay = date.getUTCFullYear() + '-' + padTo2Digits(date.getUTCMonth() + 1) + '-' + padTo2Digits(date.getUTCDate());
            var messOwner = ''
            var isMe;
            if (data.initiator_type == 'user') {
                messOwner = data.user.first_name + ` ` + data.user.last_name;
                isMe = false;
            } else {
                messOwner = data.customer.first_name + ` ` + data.customer.last_name;
                isMe = true;
            }

            /************************ */
            return `<div class="chat-message-` + (isMe ? `right` : `left`) + ` pb-4">
                                    <div>
                                        <img src="https://bootdey.com/img/Content/avatar/avatar` + (isMe ? 1 : 2) + `.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
                                        <div class="text-muted small text-nowrap mt-2">` + hoursAndMinutes + `</div>
                                    </div>
                                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 m` + (isMe ? `r` : `l`) + `-3">
                                        <div class="font-weight-bold mb-1">` + messOwner + `</div>
                                        ` + data.message + `
                                        <div class="text-mute"><small>` + yearMonthDay + `</small></div>
                                    </div>
                                </div>`;
        }

        function padTo2Digits(num) {
            return String(num).padStart(2, '0');
        }

        function scrollBottom() {
            $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
        }

        /************** */
        var axiosHeader = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer <?= $token ?>',
            'Tenant': '<?= $tenant ?>'
        };
        /************** */
        // Chat history loading request: /admin/customer/chat-message-history
        axios.post('/customer/chat-message-history', {}, {
                headers: axiosHeader
            })
            .then((response) => {
                var chatMessages = $(".chat-messages");
                chatMessages.html('');
                $.each(response.data.message, function(i, item) {
                    chatMessages.append(chatMessageBody(item));
                    scrollBottom();
                });
                $(".customer-chat-notifications").html(response.data.unread);
            })
            .catch(error => {
                alert(error.response.data.message);
            });

        /************** */
        $('.action-send').on('click', function() {
            $('.action-send').html('Wait');
            $('.action-send').addClass('btn-warning').removeClass('btn-primary');
            axios.post('/customer/chat-message-send', {
                    'message': $('.user-message').val()
                }, {
                    headers: axiosHeader
                })
                .then((response) => {
                    $('.action-send').html('Send');
                    $('.action-send').addClass('btn-primary').removeClass('btn-warning');
                })
                .catch(error => {
                    $('.action-send').html('Send');
                    $('.action-send').addClass('btn-primary').removeClass('btn-warning');
                    alert(error.message);
                });
            $('.user-message').val('');
        });

        /**************** */
        var channel_notify = pusher.subscribe('private-chatnotificationcustomer.<?= $customer_id ?>');
        channel_notify.bind('chat_notification', function(data) {
            $(".customer-chat-notifications").html(data.total);
        });
        /*************** */
    </script>
</body>

</html>
