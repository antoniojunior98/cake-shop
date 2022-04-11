# cake-shop
## Sobre a Aplicação

Cake shop é uma aplicação web para exibir seus bolos, funcionalidades:

- Login.
- Lista de usuário.
- Cadastro de usuário: ao usuário se cadastrar a api envia um E-mail de boas vindas para o usuário.
- Edição de usuário: ao atualizar um usuário a api envia um E-mail informando que ouve uma alteração no usuário.
- deletar usuário: ao excluir um usuário a api envia um E-mail informando que o usuário foi excluido.
- Lista de categoria do produto.
- Cadastro de categoria.
- Edição de categoria.
- Deletar categoria.
- Lista de tipo de massas.
- Cadastro de massa.
- Edição de massa.
- Deletar massa.
- Lista de bolos.
- Cadastro de bolo.
- Edição de bolo.
- Deletar bolo.
- Lista de clientes.
- Cadastro de cliente.
- Edição de cliente.
- Deletar Cliente. 

## Iniciando o banco de dados mysql
Existe duas formas para iniciarmos o nosso banco de dados, a primeira é importa o banco de dados que está no projeto na pasta data_base e a segunda é rodar as migrations no laravel com o comando 'php artisan migrate'

## Iniciando o backend da aplicação
No laravel iremos iniciar o servidor próprio do artisan e queue.
- php artisan serve
- php artisan queue:work

## Rodando command para o sistema envia o produto diariamente
Esse comando já está configurado no kernel para rodar diariamente '$schedule->command('product:send-daily')->daily()'.
- product:send-daily

## Endpoint
O arquivo json 'cake_shop.postman_collection.json' contém todos os endpoint com os parametros, 
Você pode abrir seu postman ou outro software similar e importar o arquivo com todas as requisições
para seu software.

- Adicionar Usuário: post 'http://127.0.0.1:8000/api/user/add'
- Login: post 'http://127.0.0.1:8000/api/login'
- Logout: get 'http://127.0.0.1:8000/api/logout'

- Lista de usuários: get 'http://127.0.0.1:8000/api/users'
- Usuário: get 'http://127.0.0.1:8000/api/user/{id}/show'
- Editar usuário: put 'http://127.0.0.1:8000/api/user/{id}/update'
- Excluir usuário: delete 'http://127.0.0.1:8000/api/user/{id}/delete'

-Lista de categorias: get 'http://127.0.0.1:8000/api/categories'
-Adicionar categorias: post 'http://127.0.0.1:8000/api/category/store'
-Categoria: get 'http://127.0.0.1:8000/api/category/{id}/show'
-Editar categoria: put 'http://127.0.0.1:8000/api/category/{id}/update'
-Excluir categoria: delete 'http://127.0.0.1:8000/api/category/{id}/delete'

-Lista de massas: get 'http://127.0.0.1:8000/api/dough'
-Adicionar massa: post 'http://127.0.0.1:8000/api/dough/store'
-Massa: get 'http://127.0.0.1:8000/api/dough/{id}/show'
-Editar massa: put 'http://127.0.0.1:8000/api/dough/{id}/update'
-Excluir massa: delete 'http://127.0.0.1:8000/api/dough/{id}/delete'

-Lista de produtos: get 'http://127.0.0.1:8000/api/products'
-Adicionar produto: post 'http://127.0.0.1:8000/api/product/store'
-Produto: get 'http://127.0.0.1:8000/api/product/{id}/show'
-Editar produto: put 'http://127.0.0.1:8000/api/product/{id}/update'
-Excluir produto: delete 'http://127.0.0.1:8000/api/product/{id}/delete'

-Lista de clientes: get 'http://127.0.0.1:8000/api/customers'
-Adicionar cliente: post 'http://127.0.0.1:8000/api/customer/store'
-Editar cliente: put 'http://127.0.0.1:8000/api/customer/{id}/update'
-Excluir cliente: delete 'http://127.0.0.1:8000/api/customer/{id}/delete'
