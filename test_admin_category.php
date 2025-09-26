<?php

require_once 'vendor/autoload.php';

// Simular uma requisição AJAX para criar categoria como admin
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simular dados de requisição
$request = Illuminate\Http\Request::create('/portfolio/categories', 'POST', [
    'name' => 'Teste Admin Category',
    'description' => 'Categoria de teste para admin',
    'is_active' => true,
    '_token' => 'test-token'
], [], [], [
    'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
    'HTTP_ACCEPT' => 'application/json',
    'HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded'
]);

// Simular autenticação como admin (user_id = 1)
$user = new \App\Models\User();
$user->id = 1;
$user->is_admin = true;
$user->name = 'Admin Test';
$user->email = 'admin@test.com';

// Simular login
auth()->login($user);

echo "User authenticated: " . (auth()->check() ? 'Yes' : 'No') . "\n";
echo "User ID: " . auth()->id() . "\n";
echo "Is Admin: " . (auth()->user()->is_admin ? 'Yes' : 'No') . "\n";
echo "Request expects JSON: " . ($request->expectsJson() ? 'Yes' : 'No') . "\n";
echo "Headers: " . json_encode($request->headers->all()) . "\n";

// Testar o controller diretamente
$controller = new \App\Http\Controllers\PortfolioCategoryController();

try {
    $response = $controller->store($request);
    echo "Response type: " . get_class($response) . "\n";
    echo "Response content: " . $response->getContent() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}