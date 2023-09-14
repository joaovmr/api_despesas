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

MAIL_DRIVER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME={seu_email}
MAIL_PASSWORD={sua_senha_gerada}
MAIL_ENCRYPTION=ssl

Para o email foi escolhido usar o SMTP usando gmail. Para isso é necessário uma conta gmail com autenticação de dois fatores e gerar uma chave para o localhost.
Basta seguir esse link para a configuração: https://stackoverflow.com/questions/72577189/gmail-smtp-server-stopped-working-as-it-no-longer-support-less-secure-apps

Para a fila de notifications adicione QUEUE_CONNECTION=database no seu env.
Por facilidade a fila está sendo armazenada diretamente no banco na tabela de jobs
Para que a fila de notificações de email seja executada é necessário iniciar a fila com o comando php artisan queue:work.
Uma alternativa seria utilizar nohup php artisan queue:work --daemon & para manter a fila rodando em segundo plano.

Além das variáveis padrão contidas no env.example.

Para inicializar a api, entre no projeto e execute php artisan serve. A partir desse momento a aplicação estará rodando na porta 8000 do seu localhost.

Lembre de executar as migrações antes

Para a execução da aplicação consumidora do frontend, entre na pasta quasar-project e execute o comando yarn dev 9000 do seu localhost

A aplicação foi construída usando boas práticas de Laravel. Para os processos de autenticação foi utilizada a biblioteca Sanctum.

Testes unitários foram implementados e podem ser executados através do comando php artisan test



Possíveis melhorias não implementadas por prioridade:

 - Validação dos campos do formulário de login no frontend
 - Validação dos campos do formulário de cadastro no frontend
 - Melhoria na estilização do projeto
 - Separação dos componentes Vue dos templates no frontend
 - Implementação de um docker com supervisor para gerência automática de fila.
