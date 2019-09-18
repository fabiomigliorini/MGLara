 
# MGapps
### Início
Clone os repositórios a seguir em uma mesma pasta:

<pre>
git clone https://github.com/fabiomigliorini/MGdb.git
git clone https://github.com/fabiomigliorini/MGLara.git
git clone https://github.com/fabiomigliorini/MGsis.git
git clone https://github.com/fabiomigliorini/MGUplon
git clone https://github.com/fabiomigliorini/MGspa
</pre>

### MGdata
Inície o container
<pre>
$ ./start
</pre>
Importe o banco de dados
<pre>
$ ./copiar-base-producao
</pre>

### MGLara
Copiar o arquivo `.env` para a raíz do projeto

Inície o container
<pre>
$ ./start
</pre>
Acesse o container
<pre>
$ ./shell
</pre>
Certifique-se que esteja no diretório `/opt/www/MGLara/`

Instale as dependências
<pre>
$ composer install
</pre>
> _Pode dar um erro “Class 'Memcached' not found“, mas não tem problema._

Acesse pelo endereço endereço: http://localhost/MGLara/

#### Copiando as imagens
Ainda dentro do container e no diretório `/opt/www/MGLara/`

<pre>
$ cd public/
$ rsync -uva super@netuno.mgpapelaria.com.br:/opt/www/MGLara/public/imagens/ imagens/
</pre>

### MGsis
Copiar o arquivo `.env` do diretório `protected`

Inície o container
<pre>
$ ./start
</pre>
Acesse pelo endereço endereço: http://localhost:82/MGsis/

### MGUplon
Copiar o arquivo `.env` para a raíz do projeto

Inície o container
<pre>
$ ./start
</pre>

Acesse o container
<pre>
$ ./shell
</pre>

Instale as dependências
<pre>
$ composer install
</pre>
Acesse pelo endereço endereço: http://localhost:81/MGUplon

### MGspa
Copiar o arquivo `.env` para o diretório `laravel/`

Copiar o arquivo `.env` para o diretório `quasar/`

Alterar a propriedade API_URL do .env do **quasar** para:
<pre>
API_URL=http://localhost:91/api/v1/
</pre>
Inície o container
<pre>
$ ./start
</pre>
Acesse o container
<pre>
$ ./shell
</pre>
Instale as dependências do **Laravel**
<pre>
$ cd laravel/
$ composer install
</pre>
Instale as dependências do **Quasar**
<pre>
$ cd ../quasar/
$ npm install
</pre>

Compile o projeto
<pre>
$ quasar build -m pwa
</pre>

Copie os arquivos compilados para o diretório `producao/`
<pre>
$ rsync -uva dist/pwa-mat/ ../producao/pwa/
</pre>
Acesse pelo endereço endereço: http://localhost:83/