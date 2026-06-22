<?php
namespace App\Core;

class Controller {
    
    protected function render($view, $data = [], $layout = 'admin') {
    extract($data);
    
    $caller = get_called_class();
    $controllerName = str_replace('Controller', '', basename(str_replace('\\', '/', $caller)));
    
    $baseDir = 'C:/xampp/htdocs/musee/musee_national/app/';
    
    // LAYOUT 'auth' : Vue autonome
    if ($layout === 'auth') {
        $viewFile = $baseDir . 'views/auth/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("Vue introuvable : $viewFile");
        }
        require $viewFile;
        return;
    }
    
    // LAYOUT 'public' : Layout public
    if ($layout === 'public') {
        if (strpos($controllerName, 'Public') === 0) {
            $controllerName = substr($controllerName, 6);
        }
        $controllerName = strtolower($controllerName);
        
        $viewFile = $baseDir . 'views/public/' . $controllerName . '/' . $view . '.php';
        $headerFile = $baseDir . 'views/layout/public_header.php';
        $footerFile = $baseDir . 'views/layout/public_footer.php';
    } else {
        // LAYOUT 'admin' (par défaut)
        $viewFile = $baseDir . 'views/' . $controllerName . '/' . $view . '.php';
        $headerFile = $baseDir . 'views/layout/header.php';
        $footerFile = $baseDir . 'views/layout/footer.php';
    }
    
    if (!file_exists($viewFile)) {
        throw new \Exception("Vue introuvable : $viewFile");
    }
    
    if (file_exists($headerFile)) {
        require $headerFile;
    }
    require $viewFile;
    if (file_exists($footerFile)) {
        require $footerFile;
    }
}
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}