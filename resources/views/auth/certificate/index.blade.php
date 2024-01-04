<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <title>Certificate Verification</title>
    <style>
        .card {
            width: 350px;
            padding: 10px;
            border-radius: 20px;
            background: #fff;
            border: none;
            height: relative;
            position: relative;
        }

        .container {
            height: 100vh;
        }

        body {
            background: #eee;
        }

        .mobile-text {
            color: #989696b8;
            font-size: 15px;
        }

        .form-control {
            margin-right: 12px;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #ff8880;
            outline: 0;
            box-shadow: none;
        }

        .cursor {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center container">
        <div class="card py-5 px-3">
            <h5 class="m-0 mb-3 text-center">Certificate Verification</h5>
            <span class="mobile-text text-center">
                Please submit your valid <code>Certificate</code> to continue
            </span>
            @if (session()->has('error'))
                <div class="alert alert-danger mt-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('verify.login') }}" enctype="multipart/form-data">
                @csrf
                <div class="d-flex flex-row mt-3">
                    <input type="file" name="certificate" class="form-control">
                </div>
                <div class="text-center mt-4">
                    <button class="btn btn=xs btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
