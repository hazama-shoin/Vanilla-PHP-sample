<?php

namespace App\Routes;

/**
 * ルーティング
 */
class Router
{
    private static $singleton;

    /**
     * インスタンスを生成する
     */
    public static function getInstance(): mixed
    {
        $class = get_called_class();

        if (!isset(self::$singleton[$class])) {
            self::$singleton[$class] = new $class();
        }
        return self::$singleton[$class];
    }

    /**
     * ルーティング
     *
     * @param string $method メソッド GET|POST|PUT|DELETE
     * @param array $server $_SERVERを格納
     * @param array $request $_REQUESTを格納
     * @param string $csrfToken
     */
    public function route(string $method, array $routes, array $request, string $csrfToken = ''): void
    {
        $requestData = $request;
        $json = file_get_contents('php://input');
        if (!empty($json)) {
            $requestData = array_merge($request, json_decode($json, true) ?? []);
        }

        // CSRFトークン検証
        if ($method !== 'GET' && !$this->verifyCsrfToken($csrfToken)) {
            $this->response(400);
            return;
        }

        $isAuthenticated = $this->verifyAuthenticated();
        switch ($method) {
            case 'GET':
                $this->get($routes, $requestData, $isAuthenticated);
                break;
            case 'POST':
                $this->post($routes, $requestData, $isAuthenticated);
                break;
            case 'PUT':
                $this->put($routes, $requestData, $isAuthenticated);
                break;
            case 'DELETE':
                $this->delete($routes, $requestData, $isAuthenticated);
                break;
            default:
                $this->response(404);
                break;
        }
    }

    /**
     * GETメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function get(array $routes, array $request, bool $isAuthenticated = false): void
    {
        $this->response(404);
    }

    /**
     * POSTメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function post(array $routes, array $request, bool $isAuthenticated = false): void
    {
        $this->response(404);
    }

    /**
     * POSTメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function put(array $routes, array $request, bool $isAuthenticated = false): void
    {
        $this->response(404);
    }

    /**
     * DELETEメソッド
     *
     * @param string $route
     * @param array $request
     * @param bool $isAuthenticated 認証済みか否か
     */
    protected function delete(array $routes, array $request, bool $isAuthenticated = false): void
    {
        $this->response(404);
    }

    /**
     * 認証済みか確認
     *
     * @return bool 認証済み: true, 認証済みではない: false
     */
    protected function verifyAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * CSRFトークン検証
     *
     * @param string $csrfToken CSRFトークン
     * @return bool
     */
    protected function verifyCsrfToken(string $csrfToken): bool
    {
        return (
            isset($_SESSION['csrf_token'])
            && $_SESSION['csrf_token'] === $csrfToken
        );
    }

    /**
     * レスポンスを返す
     *
     * @param int $statusCode
     * @param string $message
     */
    protected function response(int $statusCode = 500, string $message = ''): void
    {
        http_response_code($statusCode);
        $responseMessage = $message;
        if (empty($message)) {
            switch ($statusCode) {
                case 200:
                    $responseMessage = '処理に成功しました。';
                    break;
                case 400:
                    $responseMessage = '不正なリクエストです。';
                    break;
                case 401:
                    $responseMessage = '認証されていません。';
                    break;
                case 404:
                    $responseMessage = 'リソースが存在しません。';
                    break;
                case 500:
                    $responseMessage = '何らかのエラーが発生しました。';
                    break;
                default:
                    break;
            }
        }
        echo json_encode(['status_code' => $statusCode, 'message' => $responseMessage]);
        return;
    }
}
