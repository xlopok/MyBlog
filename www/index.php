<?php

try {

    function myAutoLoader(string $className)
    {
        require_once __DIR__ . '/../src/' . $className . '.php';
    }

    spl_autoload_register('myAutoLoader');

    $route = $_GET['route'] ?? '';
    $routes = require_once __DIR__ . '/../src/routes.php';

    $isRouteFound = false;

    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {
        throw new \MyProject\Exceptions\NotFoundException();
    }

    unset($matches[0]);

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);
} catch (\MyProject\Exceptions\DbException $e) {
    $view = new MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage(), 'user' => \MyProject\Services\UsersAuthService::getUserByToken()], 'Ошибка', 500);
} catch (\MyProject\Exceptions\NotFoundException $e) {
    $view = new MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage(), 'user' => \MyProject\Services\UsersAuthService::getUserByToken()], '404', 404);
} catch (\MyProject\Exceptions\UnauthorizedException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage(), 'user' => \MyProject\Services\UsersAuthService::getUserByToken()], 401, 401);
} catch (\MyProject\Exceptions\Forbidden $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage(), 'user' => \MyProject\Services\UsersAuthService::getUserByToken()], 403, 403);
}catch (\MyProject\Exceptions\ForbiddenException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage(), 'user' => \MyProject\Services\UsersAuthService::getUserByToken()], 403, 403);
}