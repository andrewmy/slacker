<?php

if (!is_readable('./config.php')) {
    echo "Please create a config.php file like shown in config.php.dist\n";
    return 1;
}

require_once './config.php';

if (!defined('API_URL') || empty(API_URL)) {
    echo "Please set a valid API_URL\n";
    return 1;
}

/**
 * Send a Slack message
 *
 * @param string $from
 * @param string $addressee
 * @param string $message
 * @param array $fields
 * @param string $icon
 * @param string $iconEmoji
 * @param string $image
 * @param string $color
 * 
 * @return mixed
 */
function send(
    string $from, string $addressee, string $message,
    array $fields = [], string $icon = null, string $iconEmoji = null,
    string $image = null, string $color = null
) {
    $params = [
        "channel" => $addressee,
        "icon_url" => $icon,
        "icon_emoji" => $iconEmoji,
        'username' => $from,
        'attachments' => [
            0 => [
                "color" => $color,
                "text" => $message,
                "fields" => $fields,
                'image_url' => $image,
                'thumb_url' => $image,
            ],
        ]
    ];
    $data = "payload=".\json_encode($params);

    $ch = \curl_init(API_URL);
    \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    \curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = \curl_exec($ch);
    \curl_close($ch);

    return $result;
}

if ($argc < 3 || in_array($argv[1], ['--help', '-help', '-h', '-?', 'help'])) {
    print "
  Usage: {$argv[0]} <to> <project>
  <to>: @username or #channel
  <project>: project codename.

  --help, -help, -h, -?, help: this help.
  
  Values are not sanitized, let's hope you're not a hacker.
";
    return 0;
}

$addressee = $argv[1];
$project = $argv[2];

if (!in_array(substr($addressee, 0, 1), ['@', '#'])) {
    echo "<to> must be a @username or a #channel\n";
    return 1;
}

$result = send(
    'AutoDeploy',
    $addressee,
    $project.' deployed to WASP!',
    [],
    null,
    ':robot_face:'
);

echo "$result\n";

return 0;
