# kibana-proxy
Makes it possible to use AWS Elasticsearch provided Kibana that's available only inside the VPC.

It's made for PHP framework - Symfony 4 but you could easily write something similar for other language or framework.

NOTICE:
The simplest way to create a proxy for Kibana was to allow it to use Kibana's default URLs.
That's why you need to enable the proxy for `/_plugin/kibana/{resource}` URL.
