Setup do projeto:

O projeto consistia na realização de um CRUD em phpLaravel + Vue/Quasar para avaliação técnica.

O projeto é dividido em uma API REST e uma aplicação front end.

Para executar a API, configure no env as variáveis de banco:

DB_CONNECTION

DB_HOST

DB_PORT

DB_DATABASE

DB_USERNAME

DB_PASSWORD

E também as variáveis de email:

MAIL_DRIVER

MAIL_HOST

MAIL_PORT

MAIL_USERNAME

MAIL_PASSWORD

MAIL_ENCRYPTION

Além das variáveis padrão contidas no env.example.

Para inicializar a api, entre no projeto e execute php artisan serve. A partir desse momento a aplicação estará rodando na porta 8000 do seu localhost.

Para a execução da aplicação consumidora do frontend, entre na pasta quasar-project e execute o comando yarn dev 9000 do seu localhost

A aplicação foi construída usando boas práticas de Laravel. Para os processos de autenticação foi utilizada a biblioteca Sanctum.

Testes unitários foram implementados e podem ser executados através do comando php artisan test

Para que a fila de notificações de email seja executada é necessário iniciar a fila com o comando php artisan queue:work

Possíveis melhorias não implementadas por prioridade:

 - Validação dos campos do formulário de despesa no frontend
 - Validação dos campos do formulário de login no frontend
 - Validação dos campos do formulário de cadastro no frontend
 - Melhoria na estilização do projeto
 - Separação dos componentes Vue dos templates no frontend
 - Implementação de um docker com supervisor para gerência automática de fila.
