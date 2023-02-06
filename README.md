# Testello
Processador de tabelas com preço de fretes por cliente

Ambiente
--
Com o intuito de agilizar a preparação do ambiente de desenvolvimento com docker, foi utilizado o pacote [Laravel Sail](https://laravel.com/docs/9.x/sail).
Para subir os containers, execute o seguinte comando na pasta raiz do projeto
```shell
./vendor/bin/sail up
```

Execute o seguinte comando para criar um link simbólico com a pasta pública, necessário para realizar os uploads/downloads de arquivos
```shell
./vendor/bin/sail php artisan storage:link
```

Banco de dados
--

O esquema de tabelas construído para guardar os dados do teste pode ser visualizado no diagrama, gerado pelo [PHPStorm](https://www.jetbrains.com/help/phpstorm/creating-diagrams.html), a seguir:
![database schema](./docs/testello-database.png)
 

Teste
--
###
Acesse o link `localhost:80`, selecione o arquivo que está em [./docs/price-table (1).csv](docs%2Fprice-table%20%281%29.csv) e clique no botão `Enviar`.
Após terminar de fazer o upload do arquivo a controller irá disparar o job para processar em background o arquivo enviado.

Para executar os testes rode o seguinte comando:
```shell
./vendor/bin/sail artisan test
```
