<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Shorten Link</title>
  </head>
  <body>
    <div class="container mt-5">
        <h1>URL shortener using Laravel</h1>
        @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <form method="post" action="{{ route('generate.shorten.link.post') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" name="link" class="form-control" placeholder="Enter URL">
                        <div class="input-group-addon">
                            <button class="btn btn-succes"> Generate Shorten Link</button>
                        </div>
                        <br>
                    </div>
                    @error('link') <p class="m-0 p-0 text text-danger">{{ $message }}</p> @enderror
                </form>
            </div>
        </div>
        <table class="table table-bordered">
            <thread>
                <tr>
                    <th>ID</th>
                    <th>Short Link</th>
                    <th>Link</th>
                </tr>
            </thread>

            <tbody>
                @foreach($shortLinks as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td><a hred="{{ route('shorten.link' ,$row->code )}}" target="_blank">{{ route('shorten.link' ,$row->code)}}</a></td>
                    <td>{{ $row->link }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </body>
</html>