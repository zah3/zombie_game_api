<?php
return [
    'url' => env('FRONTEND_URL', 'http://127.0.0.1:8000'),
    // path to my frontend page with query param queryURL(temporarySignedRoute URL)
    'email_verify_url' => env('FRONTEND_EMAIL_VERIFY_URL', '/verify?queryURL='),
];