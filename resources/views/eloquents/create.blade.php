<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <title>Создание Eloquent</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-warning">
        <div class="container-fluid">
            <a class="navbar-brand h1" href={{ route('eloquents.index') }}>CRUDEloquent</a>
            <div class="justify-end ">
                <div class="col ">
                    <a class="btn btn-sm btn-success" href={{ route('eloquents.create') }}>Добавление Eloquent</a>
                </div>
            </div>
    </nav>

    <div class="container h-100 mt-5">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-10 col-md-8 col-lg-6">
                <h3>Добавление Eloquent</h3>
                <form action="{{ route('eloquents.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Наименование</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Статус</label>
                        <select name="status" id="status">
                        @foreach(App\Enums\EloquentStatusEnum::values() as $key=>$value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
                        </select>
						
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Создать Eloquent</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
