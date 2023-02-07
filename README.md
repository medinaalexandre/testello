# Testello
Processador de tabelas com preço de fretes por cliente

## Sumário
- [Configuração do ambiente](#configuração-do-ambiente)
    - [Utilizando script](#utilizando-script)
    - [Passo a passo](#passo-a-passo)
- [Funcionalidades](#funcionalidades)
    - [Processamento do CSV](#processamento-do-csv)
- [Banco de dados](#banco-de-dados)
- [Testes](#testes)

Configuração do ambiente
--
### Utilizando script
```shell
chmod +x ./install.sh && ./install.sh
```

----
### Passo a passo
Primeiro, instale os pacotes necessários para rodar o projeto via [composer](https://getcomposer.org/)
```shell
composer install
```

Crie uma copia do arquivo de environment
```shell
cp .env.example .env
```

Com o intuito de agilizar a preparação do ambiente de desenvolvimento com docker, foi utilizado o pacote [Laravel Sail](https://laravel.com/docs/9.x/sail).
Para subir os containers, execute o seguinte comando na pasta raiz do projeto
```shell
./vendor/bin/sail up -d
```

Execute o seguinte comando para criar um link simbólico com a pasta pública, necessário para realizar os uploads/downloads de arquivos
```shell
./vendor/bin/sail php artisan storage:link
```

Rode as migrations para criar e popular o banco de dados
```shell
./vendor/bin/sail php artisan migrate:fresh --seed
```

Em seguida, crie a chave de criptografia necessário para o projeto Laravel
```shell
./vendor/bin/sail php artisan key:generate
```

Execute o seguinte comando para iniciar o supervisor que irá receber os jobs
```shell
./vendor/bin/sail php artisan horizon
```

Funcionalidades
--
O upload do arquivos é feito utilizando o pacote [laravel-plupload](https://github.com/jildertmiedema/laravel-plupload),
que permite fazer o upload dos arquivos em chunks, assim evitando timeout da requisição em caso de arquivos.

O processamento dos arquivos csv é realizado em background, pelo [sistema de filas do laravel](https://laravel.com/docs/9.x/queues), utilizando
o [Redis](https://redis.io/) como mensageiro.

Os status dos jobs disparados podem ser acompanhados em [localhost/horizon](http://localhost/horizon)


### Processamento do CSV
A lógica utilizada para a validação dos dados se encontra em [DeliveryDataParser](./app/Services/DeliveryDataParser.php).
O processamento dos dados e inserção no banco de dados está no job [ProcessCustomerDeliveryCsv](./app/Jobs/ProcessCustomerDeliveryCsv.php).

Banco de dados
--

O esquema de tabelas construído para guardar os dados do teste pode ser visualizado no diagrama, gerado pelo [PHPStorm](https://www.jetbrains.com/help/phpstorm/creating-diagrams.html), a seguir:
![database schema](./docs/testello-database.png)
Foi separado os dados de preço por faixa de peso em uma tabela separada da localização pela seguinte lógica:
Se este processo ocorre cerca de 1x/mês para cada cliente, foi decidido ter um processo mais custoso na inserção
(bulk somente por custo por faixa de peso), para durante o mês inteiro ter mais fácil acesso aos dados na leitura. 

Testes
--
###
Acesse o link `localhost:80`, selecione o arquivo que está em [./docs/price-table (1).csv](docs%2Fprice-table%20%281%29.csv) e clique no botão `Enviar`.
Após terminar de fazer o upload do arquivo a controller irá disparar o job para processar em background o arquivo enviado.

Para executar os testes rode o seguinte comando:
```shell
./vendor/bin/sail artisan test
```
