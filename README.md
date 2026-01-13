<!-- composer require laravel/sanctum
php artisan sanctum:install
php artisan migrate



routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::post('/login', function (Request $request) {
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Wrong data'], 401);
    }

    return response()->noContent();
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



📌 Axios (resources/js/bootstrap.js)
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'







