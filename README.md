# Hafas Client for PHP

[![PHPUnit](https://github.com/MrKrisKrisu/hafas-client-php/actions/workflows/phpunit.yml/badge.svg)](https://github.com/MrKrisKrisu/hafas-client-php/actions/workflows/phpunit.yml)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/581efdb930e7487d99edb6cf464ba96d)](https://www.codacy.com/gh/MrKrisKrisu/hafas-client-php/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MrKrisKrisu/hafas-client-php&amp;utm_campaign=Badge_Grade)
[![Gitmoji](https://img.shields.io/badge/gitmoji-%20😜%20😍-FFDD67.svg)](https://gitmoji.dev)
![License](https://img.shields.io/github/license/MrKrisKrisu/hafas-client-php)

## Installation**

```bash
composer require mrkriskrisu/hafas-client-php
```

> :warning: This library is just being developed and structured. With each version the structure will change. So use this
> library with care and specify an exact version in your composer.json (don't use <b>^</b>0.x!) until it reaches version 1.

## Features

- Fetch [next departures](https://github.com/MrKrisKrisu/hafas-client-php/blob/main/examples/FetchDepartures.php) of a
  station

- Fetch [next arrivals](https://github.com/MrKrisKrisu/hafas-client-php/blob/main/examples/FetchArrivals.php) of a
  station

- Fetch [locations](https://github.com/MrKrisKrisu/hafas-client-php/blob/main/examples/FetchLocation.php)

- Fetch [journeys](https://github.com/MrKrisKrisu/hafas-client-php/blob/main/examples/FetchJourney.php)

- Fetch [nearby stops](https://github.com/MrKrisKrisu/hafas-client-php/blob/main/examples/FetchNearby.php)

## Related

- [`hafas-client`](https://github.com/public-transport/hafas-client) – JavaScript client for HAFAS public transport
  APIs.

- [`db-hafas`](https://github.com/public-transport/db-hafas) – JavaScript client for the Deutsche Bahn HAFAS API.
