<!doctype html>
<html>

<head>
    <meta charset='utf-8' />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            padding-top: 70px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Bootstrapテスト</a>
            </div>
        </nav>
    </header>

    <div class="container">
        本文
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th scope="col">見出し</th>
                <th scope="col">見出し</th>
                <th scope="col">見出し</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>テーブルのセル</td>
                <td>テーブルのセル</td>
                <td>テーブルのセル</td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>テーブルのセル</td>
                <td>テーブルのセル</td>
                <td>テーブルのセル</td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td>テーブルのセル</td>
                <td>テーブルのセル</td>
                <td>テーブルのセル</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
