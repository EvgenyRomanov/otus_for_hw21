# PHP_2023

https://otus.ru/lessons/razrabotchik-php/?utm_source=github&utm_medium=free&utm_campaign=otus

Решаемая бизнес-задача - создание абстрактной заявки, у которой есть статус.  
В корне проекта yaml файл с описанием API.  
При чтении сообщений из очереди добавлена задержка, как имитация обработки заявки, после которой меняется статус.  

Выполнение миграций:  
```php
vendor/bin/phinx migrate -e production
```
