<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <title>CRUDEloquent</title>
</head>




<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-warning">
        <div class="container-fluid">
            <a class="navbar-brand h1" href={{ route('eloquents.index') }}>CRUDEloquent</a>
            <div>
			<div class="col">


					<form action="{{ route('eloquents.sozdanall') }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger btn-sm">Генерировать строки</button>
                                    </form>
                </div>
				<div class="col">

					<form action="{{ route('eloquents.deleteall') }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger btn-sm">Удалить строки</button>
                                    </form>
                </div>
                <div class="col">
                    <a class="btn btn-sm btn-success" href={{ route('eloquents.create') }}>Добавление Eloquent</a>
                </div>
            </div>
    </nav>
    <div class="container mt-5">
    <table>
    <form action="{{ route('eloquents.sohrgogl') }}" method="post">
    <tr>

    <div class="form-group">
                        <label for="name">ID Пути к google таблице</label>
                        <input type="text" value="1vwZy3l5Tx7wMrbGSc-AGAtEF9X4e7LkdZq2uzPa0u9o" class="form-control" id="sohrangogl" name="sohrangogl" required>
                    </div>
    </tr>
    <tr>

    <div class="form-group">
    <div>
                        <label for="name0">Пока сделал соединение 2 файлов</label>
                            </div>
    <div>
                        <label for="name1">Путь к файлу: https://docs.google.com/spreadsheets/d/1vwZy3l5Tx7wMrbGSc-AGAtEF9X4e7LkdZq2uzPa0u9o/edit?gid=0#gid=0</label>
                            </div><div>
                            <label for="name2">Путь к файлу: https://docs.google.com/spreadsheets/d/16t0jiax1gRuvs4yNuCZ5KgDRHt1YW15NZ1U2AETdZgE/edit?gid=0#gid=0</label>
                                </div></div>
    </tr>
    <tr>
    <div>


                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger btn-sm">Сохранить путь</button>

                </div>
                </tr>

                </form>

                <form action="{{ route('eloquents.fetchcountreg') }}" method="get">
                <tr>
                <div class="form-group">
                        <label for="name" >Выводить количество записей</label>
                        <input type="text" class="form-control" id="fetchcountreg" name="fetchcountreg" required>
                    </div>
                </tr>
                <tr>
    <div>


                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="btn btn-danger btn-sm">Записи количество в строке из Google таблицы</button>

                </div>
                </tr>
                </form>

                <form action="{{ route('eloquents.fetch') }}" method="get">

                <tr>
    <div>


                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="btn btn-danger btn-sm">Записи все из Google таблицы</button>

                </div>
                </tr>
                </form>

    </table>
    </div>
    <div class="container mt-5">
	<table>
        <div class="row">
            @foreach ($eloquents as $eloquent)
			<tr>
                <div>
                    <div class="card">
                        <div>
                            <h5 class="card-title">Наименование: {{ $eloquent->name }}</h5>
                        </div>
                        <div>
                            <p class="card-text">Статус: {{ $eloquent->status }}</p>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm">
                                    <a href="{{ route('eloquents.edit', $eloquent->id) }}"
                                        class="btn btn-primary btn-sm">Редактировать</a>
                                </div>
                                <div class="col-sm">
                                    <form action="{{ route('eloquents.destroy', $eloquent->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				</tr>
            @endforeach
        </div>
		</table>
    </div>
</body>
<script></script>
</html>
