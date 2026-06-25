<?php
namespace App\Controllers\Api;

use App\Core\Controller;

class TestApiController extends Controller {
    
    public function indexAction() {
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'API fonctionnelle !',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function testAction() {
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Test réussi !',
            'data' => [
                'test' => 'La route API fonctionne correctement'
            ]
        ]);
    }
}