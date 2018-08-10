<?php

namespace CandleLight\Middleware;

use CandleLight\Error;
use CandleLight\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Allows Pagination of a big array content that is beeing returned
 * @package CandleLight\Middleware
 */
class Pagination extends Middleware{

    /**
     * Executes the Middleware
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function apply(Request $request, Response $response, callable $next): Response{
        $response = $next($request, $response);
        $data = json_decode($response->getBody()->__toString());

        $url = $this->getUrl($request);
        $params = $request->getQueryParams();

        $page = (isset($params['p'])) ? (int)$params['p'] : 1;
        $chunk = (isset($params['count'])) ? (int)$params['count'] : 5;

        // Fill if not set
        $params['p'] = $page;

        // Throw errors if the given values are invalid
        if ($chunk <= 0) {
            return $this->errorCount($response);
        }
        if ($page <= 0) {
            return $this->errorPage($response);
        }
        if (!is_array($data)) {
            return $this->errorContent($response);
        }

        // Build current page chunk
        $size = count($data);
        $numberPages = ceil($size / $chunk);
        $currentPage = array_slice($data, $chunk * ($page - 1), $chunk, true);

        // Build Output
        $output = [
            'data' => $currentPage,
            'maxPage' => $numberPages,
            'currentPage' => $page,
        ];

        // Prev Link
        if ($page > 1) {
            $prev = $params;
            $prev['p']--;
            $output['prev'] = $url . '?' . http_build_query($prev);
        }
        // Next Link
        if ($page < $numberPages) {
            $next = $params;
            $next['p']++;
            $output['next'] = $url . '?' . http_build_query($next);
        }

        // Output
        $response = $response->withJson($output);
        return $response;
    }

    /**
     * Builds the full request url
     * @param Request $request
     * @return string
     */
    private function getUrl(Request $request): string{
        $urlObject = $request->getUri();
        return $urlObject->getScheme() . '://' . $urlObject->getAuthority() . $urlObject->getPath();
    }

    /**
     * Adds the chunk count error message to the response
     * @param Response $response
     * @return Response
     */
    private function errorCount(Response $response): Response{
        return $response->withJson(new Error('Count Parameter (?count) needs to positive'));
    }

    /**
     * Adds the page number error message to the response
     * @param Response $response
     * @return Response
     */
    private function errorPage(Response $response): Response{
        return $response->withJson(new Error('Page Parameter (?p) needs to be positive'));
    }

    /**
     * Adds the content error message to the response
     * @param Response $response
     * @return Response
     */
    private function errorContent(Response $response): Response{
        return $response->withJson(new Error('Given content is not suitable for pagination'));
    }
}

/* @var \CandleLight\App $app */
$app->addMiddleware('pagination', Pagination::class);