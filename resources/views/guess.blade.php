<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guess the Number</title>
</head>
<body>
    <h1>Guess the 4-Digit Number</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
        
    @elseif(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if(!session('gameOver'))
        <form action="/guess" method="POST">
            @csrf
            <input type="text" name="guess" maxlength="4" placeholder="Enter 4 unique digits" required>
            <button type="submit">Guess</button>
        </form>
        <p>lmo7awalt liba9i: {{ session('attempts') }}</p>
    @else
        <form action="/" method="GET">
            <button type="submit">Restart Game</button>
        </form>
    @endif
    @if(session('histo'))
    <h3>Guess History:</h3>
    <ul>
        @foreach(session('histo') as $entry)
            <li>{{ $entry }}</li>
        @endforeach
    </ul>
@endif

</body>
</html>
