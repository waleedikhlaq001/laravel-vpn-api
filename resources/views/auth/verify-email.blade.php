<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
    <div>
        @if (session('resent'))
            <div>
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <p>
            Before proceeding, please check your email for a verification link.
        </p>
        <p>
        

            If you did not receive the email,
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit"> click here to request another</button>
            </form>
           </a>.
        </p>
    </div>
</body>
</html>
