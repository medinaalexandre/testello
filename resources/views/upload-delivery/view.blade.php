<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testello - Upload CSV</title>
</head>
<body>
@if($errors->any())
    @foreach($errors->all() as $error)
        <p style="color:red;"> {{ $error }} </p>
    @endforeach
@endif
<div style="display: flex; flex-direction: column; gap: 4px;">
    <form action="{{ $action }}" method="POST" id="files-form" enctype="multipart/form-data">
        @csrf
        <label for="customer">
            Selecione o cliente
            <select name="customer_id" id="customer" required>
                @php /** @var \App\Models\Customer $customer */ @endphp
                @foreach($customers as $customer)
                    <option value="{{ $customer->getKey() }}">
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </label>
        <button type="button" id="current-browse-button">Selecione os arquivos</button>
    </form>
    <ul id="selected-files">
    </ul>
    <div>
        <button type="submit" id="send-button">Enviar</button>
    </div>
</div>
</body>
<script type="text/javascript" src="/vendor/jildertmiedema/laravel-plupload/js/plupload.full.min.js"></script>
<script type="text/javascript">
    const current_uploader = new plupload.Uploader({
        runtimes: 'html5',
        browse_button: 'current-browse-button',
        url: 'upload',
        headers: {
            Accept: 'application\/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        chunk_size: '5mb',
        max_file_size: '100mb',
        filters : {
            mime_types:[{title : "CSV files", extensions : "csv,txt"},]
        },
        init: {
            BeforeUpload: function(up, file) {
                file.target_name = (new Date).getTime() + '_' + file.name;
            },
            FilesAdded: function (up, files) {
                const filesUlElement = document.getElementById('selected-files');
                plupload.each(files, function (file) {
                    const li = document.createElement('li')
                    li.appendChild(document.createTextNode(`${file.name} (${file.size / 1000}kb)`))
                    filesUlElement.append(li);
                });
            },
            UploadComplete: function (up, files) {
                const form = document.getElementById('files-form');
                files.forEach(file => {
                    const input = document.createElement('input');
                    input.setAttribute('name', 'filenames[]');
                    input.setAttribute('value', file.target_name);
                    input.setAttribute('type', 'hidden');
                    form.append(input);
                });
                form.submit();
                alert('upload completed, the job is dispatching now');
            },
        }
    });
    current_uploader.init();

    document.getElementById('send-button').onclick = function () {
        current_uploader.start();
    };
</script>
</html>
