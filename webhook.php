<?php

if (is_readable('bot_token')) {
    $token = file_get_contents('bot_token');
}

if (empty($token)) {
    header('HTTP/2 500 Bot Token Not Configured');
    header('Content-Type: text/plain; charset=utf-8');
    exit('Bot Token Not Configured');
}

if (empty($_GET['key'])) {
    header('HTTP/2 401 Key Required');
    header('Content-Type: text/plain; charset=utf-8');
    exit('Invalid Key');
} else if (strcmp($_GET['key'], md5($token)) !== 0) {
    header('HTTP/2 403 Invalid Key');
    header('Content-Type: text/plain; charset=utf-8');
    exit('Invalid Key');
}

$update = json_decode(file_get_contents('php://input'));

if (isset($update->message->text)) {
    if (preg_match('/t\.me|telegram\.me|@([a-z][a-z0-9_]{4,})/i', $update->message->text)) {
        header('Content-Type: application/json; charset=utf-8');
        exit(json_encode(array(
            'method' => 'deleteMessage',
            'chat_id' => $update->message->chat->id,
            'message_id' => $update->message->message_id,
        )));
    }
}