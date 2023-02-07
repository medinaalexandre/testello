<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testello - Upload CSV</title>
    @vite('resources/css/app.css')
</head>
<body class="w-full h-screen flex items-center justify-center">
<div class="flex flex-col border-2 border-rose-500 rounded-md p-8 lg:w-1/4">
    <h1 class="text-2xl text-center text-rose-600 font-bold mb-4">Importar tabela de fretes</h1>
    <form
        action="{{ $action }}"
        method="POST"
        id="files-form"
        enctype="multipart/form-data"
        class="flex flex-col gap-4">
        @csrf
        <label for="customer" class="flex flex-col gap-0">
            Selecione o cliente:
            <select
                name="customer_id"
                id="customer"
                required
                class="bg-white border px-4 py-3 rounded-md border-rose-600 outline-rose-700"
            >
                @php /** @var \App\Models\Customer $customer */ @endphp
                @foreach($customers as $customer)
                    <option value="{{ $customer->getKey() }}">
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </label>
        <button
            type="button"
            id="current-browse-button"
            class="inline-block mt-4 px-4 py-3 text-sm font-semibold text-center text-rose-600 border uppercase transition duration-200 ease-in-out border-rose-600  rounded-md cursor-pointer hover:bg-rose-700 hover:text-white"
        >
            Selecione os arquivos
        </button>
    </form>
    <ul id="selected-files" class="flex flex-col my-4">
        <li>teste</li>
        <li>teste</li>
    </ul>
    <button
        type="submit"
        id="send-button"
        class="inline-block mt-4 px-4 py-3 text-sm font-semibold text-center text-white uppercase transition duration-200 ease-in-out bg-rose-600  rounded-md cursor-pointer hover:bg-rose-700"
    >
        Enviar
    </button>
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
