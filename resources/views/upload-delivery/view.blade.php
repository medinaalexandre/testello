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
<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display: flex; flex-direction: column; gap: 4px;">
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
        <label for="customer_csv">
            Selecione o arquivo
            <input type="file" name="customer_csv[]" id="customer_csv" accept="text/csv" multiple>
        </label>
        <div>
            <button type="submit">Enviar</button>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</form>
</body>
</html>
