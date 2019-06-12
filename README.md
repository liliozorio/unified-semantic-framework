###Estruturação do framework

- Todo o framework é gerenciado pelo arquivo ``Controller/Controller.php``.
    - A função **index()** é a função chamada no arquivo ```index.php```, ela inicia a execução do framework.

- Para cada site deve ser contruído uma classe de scrapping. Existem duas classes de exemplos na pasta ``scraping``, 
``PoliciaCivil.php`` e ``PoliciaMilitarSC.php``.

- As classes de scrapping que serão executadas ao iniciar o framework estão descritas no arquivo ```scraping.php```, cada
linha deve conter **uma** classe.

- Todos arquivos **.json** são armazenados na pasta ```json/nomeDaClasseScraping```. Dentro de cada pasta temos:
    - Um arquivo ``config.php``, onde estão as configurações de **Tasks** e **Methods** que serão utilizados no scraping da página web.
    - Arquivos **.json** de cada pessoa do scraping. Estes arquivos devem ser nomeados da seguinte forma ``nomeDaClasseScraping_{contador}.php``, sendo que a variável ``contador`` deve iniciar com o valor igual a "0".
      
- Na pasta ``method`` estão os **methods** e na pasta ``tasks`` estão as **tasks**, ambas serão solicitadas dependendo da configuração descrita para a página web.

- Na pasta ``model`` estão os models do sistema.

- Na pasta ``export_import`` estão as classes usadas para importação e exportação de dados.
