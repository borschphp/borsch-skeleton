<?php

namespace AppTest\Middleware;

use AppTest\App;
use AppTest\Mockup\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

class RouteMiddlewareTest extends App
{

    public function testProcessGetRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessPostRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('POST', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessPutRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PUT', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessDeleteRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('DELETE', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessHeadRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('HEAD', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessOptionsRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('OPTIONS', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessPatchRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PATCH', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessConnectRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('CONNECT', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessTraceRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('TRACE', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessPurgeRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PURGE', 'https://example.com/to/route');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithGet()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithPost()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('POST', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithPut()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PUT', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithDelete()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('DELETE', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithHead()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('HEAD', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithOptions()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('OPTIONS', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithPatch()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PATCH', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithConnect()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('CONNECT', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithTrace()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('TRACE', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessAnyRouteWithPurge()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PURGE', 'https://example.com/to/any');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessWithMatchingMethodsPOST()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('POST', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessWithMatchingMethodsPUT()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PUT', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessWithMatchingMethodsGetShouldFail()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testProcessWithMatchingMethodsDeleteShouldFail()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('DELETE', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testProcessWithMatchingMethodsHeadShouldFail()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('HEAD', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testProcessWithMatchingMethodsOptionsShouldFailAndReturnAllowedMethods()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('OPTIONS', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('Allow', $response->getHeaders());
        $this->assertEquals('POST, PUT', $response->getHeaderLine('Allow'));
    }

    public function testProcessWithMatchingMethodsPatchShouldFail()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PATCH', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testProcessWithMatchingMethodsConnectShouldFail()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('CONNECT', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testProcessWithMatchingMethodsTraceShouldFail()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('TRACE', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testProcessWithMatchingMethodsPurgeShouldFail()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PURGE', 'https://example.com/to/match');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
    }
}
