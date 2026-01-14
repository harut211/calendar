<!-- Route::middleware('auth:sanctum')->get( 
  '/google/redirect',
  [GoogleController::class, 'redirect']
);
Контроллер
php
Copy code
public function redirect()
{
    return redirect($this->client()->createAuthUrl());
}
➡ пользователь уже залогинен
➡ просто подключает Google Calendar

3️⃣ Callback Google
php
Copy code
public function callback(Request $request)
{
    $client = $this->client();
    $token = $client->fetchAccessTokenWithAuthCode($request->code);

    auth()->user()->update([
        'google_token' => encrypt(json_encode($token))
    ]);

    return redirect('/calendar');
}
📌 Это НЕ логин, а сохранение токена Google

4️⃣ Получение событий
php
Copy code
Route::middleware('auth:sanctum')->get(
  '/google/events',
  [GoogleController::class, 'events']
);
php
Copy code
public function events()
{
    $client = $this->client();
    $client->setAccessToken(
        json_decode(decrypt(auth()->user()->google_token), true)
    );

    return (new Calendar($client))
        ->events
        ->listEvents('primary')
        ->getItems();
}
5️⃣ Vue
vue
Copy code
<button @click="connectGoogle">
  Connect Google Calendar
</button>
js
Copy code
connectGoogle() {
  window.location.href = '/api/google/redirect'
}
📌 Если Google уже подключён — кнопку можно скрыть

6️⃣ Проверка подключения
php
Copy code
$user->google_token !== null
$client->setAccessType('offline');
$client->setPrompt('consent');
php
Copy code
$client->addScope(\Google\Service\Calendar::CALENDAR);
📌 prompt('consent') нужен только при первом подключении,
иначе Google не вернёт refresh_token.

2️⃣ Хранение токена (рекомендуемый формат)
В БД ОДНО поле:

php
Copy code
$table->text('google_token')->nullable();
Храним всё целиком, но зашифрованно:

php
Copy code
$user->google_token = encrypt(json_encode($token));
$token выглядит так:

json
Copy code
{
  "access_token": "...",
  "refresh_token": "...",
  "expires_in": 3599,
  "created": 1700000000
}
3️⃣ Авто-refresh при каждом запросе (КЛЮЧЕВОЕ)
✅ Правильная функция
php
Copy code
private function googleClient(User $user): \Google\Client
{
    $client = new \Google\Client();

    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect'));
    $client->addScope(\Google\Service\Calendar::CALENDAR);
    $client->setAccessType('offline');

    $token = json_decode(decrypt($user->google_token), true);
    $client->setAccessToken($token);

    // 🔁 АВТО-REFRESH
    if ($client->isAccessTokenExpired()) {
        $newToken = $client->fetchAccessTokenWithRefreshToken(
            $client->getRefreshToken()
        );

        $user->update([
            'google_token' => encrypt(json_encode(
                array_merge($token, $newToken)
            ))
        ]);
    }

    return $client;
}
4️⃣ Использование
php
Copy code
$client = $this->googleClient(auth()->user());
$service = new \Google\Service\Calendar($client);

$events = $service->events->listEvents('primary');