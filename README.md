# You know the REST, now it's time for Pest!

<table>
  <tr>
    <td>
      <p>Source code for the talk <b>"You know the REST, now it's time for Pest!"</b>.</p>
      <p><i>Testing an application which consumes external resources (APIs) may face some restrictions such as: Cost per request, rate limit, no sandbox, among others. In this talk, I present my way of testing APIS with Pest and Bypass.</i></p>
      <p>🗓️ Pest Meetup #1 - Jun, 2021</p>
      <p>📺 <a href="https://www.youtube.com/watch?v=q_8kRlAIyms" target="_blank">Watch it!</a></p>
    </td>
  </tr>
</table>

## Requirements

- PHP 8.0+
- [Git](https://github.com/git-guides/install-git)
- [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- Using:
    - [Laravel 8x](https://laravel.com/docs/8.x/)
    - [Pest PHP](https://pestphp.com)
    - [Bypass for PHP](https://bypassforphp.com)

## Get started

### Clone

Clone this repository:

```bash
git clone https://github.com/dansysanalyst/pest-bypass.git
```

Enter the project's directory:

```bash
cd pest-bypass
```

### Install

```bash
composer install
```

### Configure your .env

Copy the default file `.env.example` into `.env`

```bash
cp .env.example .env 
```

Generate the application key

```bash
php artisan key:generate
```

Serve the application:

```bash
php artisan serve
```

Running the command above should produce an output like: `Starting Laravel development server: http://127.0.0.1:8000`.


Open the file `app/Services/TourigaPhoneService.php` and verify that the Base URL matches the one being served by the Laravel development server. The variable `$baseUrl` must end with `/api`.

```php
<?php

//...

$baseUrl = 'http://127.0.0.1:8000/api';
```

Run the tests:

```bash
./vendor/bin/pest
```

## My Setup

My setup for this talk was:

- VS Code with [Palenight](https://marketplace.visualstudio.com/items?itemName=whizkydee.material-palenight-theme) theme and [Victor-mono](https://rubjo.github.io/victor-mono/) font.

- Relevant VS code extensions:

  - [PHP Awesome Snippets](https://marketplace.visualstudio.com/items?itemName=hakcorp.php-awesome-snippets)

  - [PHP CS Fixer](https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer)

  - [PHP Namespace Resolver](https://marketplace.visualstudio.com/items?itemName=MehediDracula.php-namespace-resolver)

  - [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)

  - [PHP IntelliSense](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-intellisense)

  - [PHP Sniffer](https://marketplace.visualstudio.com/items?itemName=wongjn.php-sniffer)

  - [Zen Mode with Show Terminal Button](https://marketplace.visualstudio.com/items?itemName=sketchbuch.vsc-zen-terminal-button)

  - [Pest Snippets](https://marketplace.visualstudio.com/items?itemName=dansysanalyst.pest-snippets)

- MacOS Terminal with [Oh My ZSH](https://ohmyz.sh) and `"p"` as alias for running Pest: `alias p="./vendor/bin/pest"`.

- [Laravel Valet](https://laravel.com/docs/8.x/valet) serving the demo API and demo doc.

- [Docsy](https://themes.gohugo.io/docsy/) theme for [Hugo](https://gohugo.io) in documentation demo.

## Credits

- [Pest PHP](https://pestphp.com) by [Nuno Maduro](https://github.com/nunomaduro).
- [Bypass for PHP](https://pestphp.com) by [Leandro Henrique](https://github.com/emtudo).
- Presentantion slide by [HiSlide.io](https://www.hislide.io).
