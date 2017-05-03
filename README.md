# Slacker

This is a super-simple CLI Slack pinger.

## Installation

1. Create an incoming Slack webhook integration at https://[team].slack.com/apps/
2. Copy `config.php.dist` to `config.php`
3. Enter your webhook url into the `API_URL` constant.

## Usage

`php slacker.php <to> <message>`

`<to>`: @username or #channel

Useful for pinging about deployments or something.
