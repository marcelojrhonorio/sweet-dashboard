# Sweet Media

Gerenciamento de ações, campanhas e usuários.

## Instalar em servidor local

Siga os seguintes passos para rodar o projeto em seu ambiente local de desenvolvimento.

#### 1. Clone o projeto para seu repositório local

#### 2. Faça o setup do arquivo .env

Copie o arquivo *.env.example*, renomeando para *.env*. Além das padrões, as propriedades abaixo deverão estar presentes: 

`APP_IMAGE_CAMPAIGN_URL=` Imagens das campanhas.

`APP_SWEET_API=` Informe o endereço da API do projeto.

#### 3. Atualize seu projeto
Digite o comando `composer update` dentro da pasta.

#### 4. Adicione o projeto ao Homestead
Para que o projeto rode no Homestead, é necessário incluir as informações no arquivo *Homestead.yaml*.  

Exemplo de configuração para folders:

    folders: 
       - map: C:/www/sweetmedia
         to: /home/vagrant/Sites/sweetmedia.test

Exemplo de configuração para sites:

    sites: 
       - map: sweetmedia.test
         to: /home/vagrant/Sites/sweetmedia.test/public
         schedule: true   

Lembre-se também de configurar o arquivo de *hosts* do seu sistema operacional. Veja o exemplo abaixo: 

    192.168.10.10	sweetmedia.test

