# kibana-proxy
Makes it possible to use AWS Elasticsearch provided Kibana that's available only inside the VPC.

It's made for PHP framework - Symfony 4 but you could easily write something similar for other language or framework.

NOTICE:
The simplest way to create a proxy for Kibana was to allow it to use Kibana's default URLs.
That's why you need to enable the proxy for `/_plugin/kibana/{resource}` URL.

## Requirements

- Symfony 3.x or 4.x, eg. `"symfony/framework-bundle": "^4.1"`
- Symfony PSR HTTP Message Bridge, eg. `"symfony/psr-http-message-bridge": "^1.0"`
- Zend Diactoros, eg. `"zendframework/zend-diactoros": "^1.6"`
- Guzzle HTTP, eg. `"guzzlehttp/guzzle": "~6.0"`

Eg. assuming you're already using Symfony project:
```bash
composer require "symfony/psr-http-message-bridge" "zendframework/zend-diactoros" "guzzlehttp/guzzle"
```

## Setup

1. Simply create a proxy controller like [KibanaController.php](/KibanaController.php).

2. Register it in `services.yaml` to inject Elasticsearch details.
Eg.  
```yaml
services:
    ...
    App\Controller\KibanaController:
        arguments:
            $elasticsearchHost: '%env(ELASTICSEARCH_HOST)%'
            $elasticsearchTransport: '%env(ELASTICSEARCH_TRANSPORT)%'
```

3. Set `ELASTICSEARCH_HOST` and `ELASTICSEARCH_TRANSPORT` environment variable or hardcode it above.

4. Use Kibana by opening [/_plugin/kibana/app/kibana](https://example.com/_plugin/kibana/app/kibana) 

5. (Recommended) Secure Kibana access.
Eg. in `security.yaml` add a line like:
```yaml
security:
  access_control:
    - { path: ^/_plugin/kibana/, roles: ROLE_SUPER_ADMIN, requires_channel: https }
```