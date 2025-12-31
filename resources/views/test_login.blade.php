<!DOCTYPE html>
<html>
<head>
    <title>Test Login</title>
</head>
<body>
    <h1>Simple Test Login</h1>
    <form method="POST" action="/test-login">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit">Test Login (NIDN: 1234567890)</button>
    </form>
    
    <hr>
    
    <h2>Current Auth Status:</h2>
    <p>{{ Auth::check() ? 'Logged in as: ' . Auth::user()->nidn : 'Not logged in' }}</p>
    
    @if(Auth::check())
        <a href="/dashboard">Go to Dashboard</a> | 
        <a href="/logout">Logout</a>
    @endif
</body>
</html>
