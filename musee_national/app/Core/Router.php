<?php
namespace App\Core;

class Router {
    public function dispatch() {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = rtrim($url, '/');
        $segments = explode('/', $url);

        $isApi = false;
        $isPublic = false;
        $isAdmin = false;
        $controllerName = '';
        $actionName = 'indexAction';
        $params = [];

        if (!empty($segments[0])) {
            if ($segments[0] === 'api') {
                $isApi = true;
                array_shift($segments);
            } elseif ($segments[0] === 'public') {
                $isPublic = true;
                array_shift($segments);
            } elseif ($segments[0] === 'admin') {
                $isAdmin = true;
                array_shift($segments);
            }
        }

        // Si après traitement l'URL est vide
        if (empty($segments) || $segments[0] === '') {
            // Par défaut, toujours utiliser le contrôleur public pour la page d'accueil
            $controllerName = 'PublicHomeController';
            $actionName = 'indexAction';
            $isPublic = true;
            $params = [];
        } else {
            // Détection du type de route
            if ($isAdmin) {
                // Routes admin
                $firstSegment = $segments[0];
                if ($firstSegment === 'dashboard') {
                    $controllerName = 'AdminController';
                    $actionName = 'dashboardAction';
                    $params = array_slice($segments, 1);
                } else {
                    $controllerName = ucfirst($firstSegment) . 'Controller';
                    $actionName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] . 'Action' : 'indexAction';
                    $params = array_slice($segments, 2);
                }
            } elseif ($isPublic) {
                // Routes publiques explicites
                $controllerName = 'Public' . ucfirst($segments[0]) . 'Controller';
                $actionName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] . 'Action' : 'indexAction';
                $params = array_slice($segments, 2);
            } elseif ($isApi) {
                // Routes API
                $controllerName = ucfirst($segments[0]) . 'ApiController';
                $actionName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] . 'Action' : 'indexAction';
                $params = array_slice($segments, 2);
            } else {
                // Routes sans préfixe : Vérifier si un contrôleur public existe
                $publicControllerName = 'Public' . ucfirst($segments[0]) . 'Controller';
                $publicFile = dirname(__DIR__, 2) . '/app/controllers/web/' . $publicControllerName . '.php';
                
                if (file_exists($publicFile)) {
                    // Si un contrôleur public existe, l'utiliser (toujours public)
                    $controllerName = $publicControllerName;
                    $actionName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] . 'Action' : 'indexAction';
                    $params = array_slice($segments, 2);
                    $isPublic = true;
                } else {
                    // Sinon, chercher un contrôleur normal
                    $controllerName = ucfirst($segments[0]) . 'Controller';
                    $actionName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] . 'Action' : 'indexAction';
                    $params = array_slice($segments, 2);
                }
            }
        }

        // Déterminer le dossier et le namespace
        if ($isApi) {
            $controllerDir = 'api/';
            $namespace = '\\App\\Controllers\\Api\\';
        } elseif ($isPublic) {
            $controllerDir = 'web/';
            $namespace = '\\App\\Controllers\\Web\\';
        } elseif ($isAdmin) {
            $controllerDir = 'web/';
            $namespace = '\\App\\Controllers\\Web\\';
        } else {
            $controllerDir = 'web/';
            $namespace = '\\App\\Controllers\\Web\\';
        }

        $controllerClass = $namespace . $controllerName;
        $controllerFile = dirname(__DIR__, 2) . '/app/controllers/' . $controllerDir . $controllerName . '.php';
      
        if (!file_exists($controllerFile)) {
            $this->show404($isApi);
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            $this->show404($isApi);
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionName)) {
            $this->show404($isApi);
            return;
        }

        call_user_func_array([$controller, $actionName], $params);
    }

    private function show404($isApi = false) {
        if ($isApi) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'API endpoint not found']);
        } else {
            http_response_code(404);
            echo "Page non trouvée (404)";
        }
        exit;
    }
}