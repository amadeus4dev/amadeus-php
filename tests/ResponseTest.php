<?php

declare(strict_types=1);

use Amadeus\Amadeus;
use Amadeus\Request;
use Amadeus\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Amadeus\Response
 * @covers \Amadeus\Request
 */
final class ResponseTest extends TestCase
{
    private Request $request;
    private Response $response;

    /**
     * @Before
     */
    public function setUp(): void
    {
        $info = array(
            "url" => "/foo/bar",
            "http_code" => 200,
            "header_size" => 15
        );
        $result =
            "HTTP/1.1 200 OK"
            ." "
            ."{"
            ."\"data\" : [ {"
            ." \"foo\" : \"bar\""
            ." } ]"
            ."}"
        ;
        $this->request = $this->createMock(Request::class);
        $this->response = new Response($this->request, $info, $result);
    }

    public function testInitialize(): void
    {
        $this->assertEquals($this->request, $this->response->getRequest());
    }

    public function testParse(): void
    {
        $this->assertNotNull($this->response->getInfo());
        $this->assertEquals("/foo/bar", $this->response->getUrl());
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals(15, $this->response->getHeaderSize());

        $this->assertNotNull($this->response->getResult());
        $this->assertEquals(
            "HTTP/1.1 200 OK",
            $this->response->getHeaders()
        );
        $this->assertEquals(
            " "
            ."{"
            ."\"data\" : [ {"
            ." \"foo\" : \"bar\""
            ." } ]"
            ."}",
            $this->response->getBody()
        );
    }

    public function testParseBodyData(): void
    {
        $data[] = (object) ['foo' => 'bar'];
        $this->assertEquals($data, $this->response->getData());
    }
}