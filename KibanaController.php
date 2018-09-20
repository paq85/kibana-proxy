<?php
declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Makes it possible to use AWS Elasticsearch provided Kibana that's available only inside the VPC.
 */
class KibanaController extends Controller
{
    /**
     * @var string
     */
    private $elasticsearchHost;

    /**
     * @var string
     */
    private $elasticsearchTransport;

    /**
     * @param string $elasticsearchHost eg. "vpc-west-2c-myproject-kjcndkjd333jnc.us-west-2.es.amazonaws.com"
     * @param string $elasticsearchTransport "Https" or "Http"
     */
    public function __construct(string $elasticsearchHost, string $elasticsearchTransport)
    {
        $this->elasticsearchHost = $elasticsearchHost;
        $this->elasticsearchTransport = $elasticsearchTransport;
    }

    /**
     * @Route("/_plugin/kibana/{resource}", requirements={"resource"=".+"}, name="admin-logs")
     */
    public function proxyAction($resource, Request $symfonyRequest)
    {
        $httpClient = new Client();
        $httpFoundationFactory = new HttpFoundationFactory();
        $psr7Factory = new DiactorosFactory();

        $elasticsearchUrl = $this->elasticsearchTransport . '://' . $this->elasticsearchHost;
        $requestUrl = "{$elasticsearchUrl}/_plugin/kibana/{$resource}";
        if ($symfonyRequest->getQueryString()) {
            $requestUrl .= '?' . $symfonyRequest->getQueryString();
        }
        $psrRequest = $psr7Factory->createRequest($symfonyRequest);
        $psrRequest = $psrRequest->withUri(new Uri($requestUrl));
        $psrResponse = $httpClient->send($psrRequest, ['http_errors' => false]);

        return $httpFoundationFactory->createResponse($psrResponse);
    }
}