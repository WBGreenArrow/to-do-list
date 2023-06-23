

# TO-DO-LIST


<br>

# 🎨 Preview

<img src="./demo.gif">

<br>



# 📃 Sobre o projeto

Uma aplicação fullstack feita com PHP que permite o usuario a gerenciar tarefas. Com ela, é possível adicionar, editar e remover tarefas.

<br>

# Funcionalidades

- Adicionar tarefa: O usuário é capaz de adicionar uma nova tarefa com título e descrição.

- Listar tarefas: A página inicial exibe uma lista com todas as tarefas cadastradas.

- Editar tarefa: O usuário é capaz de editar o título e a descrição de uma tarefa já existente.

- Remover tarefa: O usuário é capaz de remover uma tarefa cadastrada.


<br><br>

# 🛠 Tecnologias utilizadas

-   PHP
-   MYSQL
-   HTML
-   CSS
-   JAVASCRIPT
-  JQUERY

<br>

# 🚀 Rodando o projeto

Copie a pasta `to-do-list` para raíz do servidor apache.

Apos copiar vamos setar algumas configurações no servidor com o arquivo `.htaccess`. Entre no diretório `to-do-list/app` e recorte o arquivo`.htaccess` e cole na raíz do servidor apache. Assim ao inciamos o servidor será usado as configurações que estão definidas no arquivo `.htaccess`.

Após definir as configurações do servidor, vamos definir as configurações de conexão com o banco de dados. Acesse o arquivo `connect.php` localizado no diretório `to-do-list/app/configuration` e altere com a suas configurações de conexão do banco de dados.

exemplo: 
```php
define("HOST", "localhost");
define("DATABASENAME", "to-do-list-db");
define("USER", "admin");
define("PASSWORD", "w[[8EN9ThU4MD)0w");
```
<br>

Após configurar o banco, suba o servidor e vamos realizar a migrations.

Com o servidor rodando acesse o endereço `http://localhost/to-do-list/app/migration.php` no seu navegador, ao acessar esse endereço irá aparecer a seguinte mensagem: `Migrations executed successfully.`


Após realizar as migrations a aplicação estará disponivel para uso no endereço `http://localhost/taks`

<br>


