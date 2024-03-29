# PHP валидация (проверка) значений формы и данных

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]
[![Support mail][badge-mail]][mail]

Валидация означает проверку данных, заполняемых пользователем.
Пакет предоставляет возможность организовать проверку на стороне сервера после отправки данных формы.

## Возможности

- Проверка (валидация) данных массива и отдельного значения;
- Проверка (валидация) значений формы и загружаемых файлов;
- Поддержка набора правил со сценариями;
- Возможность изменить названия полей;
- Возможность изменить текст ошибки;
- Возможность расширить пользовательскими правилами проверки;
- Сценарии и наборы правил;
- Проверка публичных полей объекта.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/validation
```

## Проверка данных полей формы

Для проверки данных полей формы нужно использовать метод ```make``` класса валидатора ```\Fi1a\Validation\Validator```.
После создания объекта валидации необходимо вызвать метод ```validate``` для проверки переданных значений.
При вызове ```validate``` возвращается объект результата проверки ```\Fi1a\Validation\Result```.

Пример:

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make($_POST + $_FILES, [
    'id' => 'required|integer',
    'email' => 'required|email',
    'password' => 'required|minLength(8)',
    'confirm_password' => 'required|same("password")',
    'tags' => 'array|required',
    'tags:*:id' => 'required|numeric',
]);

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("\n");
}
```

## Проверка одного поля

Для проверки одного поля нужно использовать один из классов цепочки правил.
Цепочки правил реализует "Fluent interface" для объявления используемых правил. 

Цепочка "значение должно удовлетворять всем правилам" (```Fi1a\Validation\AllOf```):

```php
use Fi1a\Validation\AllOf;

$result = AllOf::create()->required()->integer()->validate('abc');

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("; "); // "Значение не является целым числом"
}
```

Цепочка "значение должно удовлетворять одному из правил" (```Fi1a\Validation\OneOf```):

```php
use Fi1a\Validation\OneOf;

$chain = OneOf::create()->integer()->boolean();

$result = $chain->validate(true);
echo $result->isSuccess(); // true

$result = $chain->validate(10);
echo $result->isSuccess(); // true

$result = $chain->validate(null);
echo $result->isSuccess(); // false
echo $result->getErrors()->join("; "); // Значение не является целым числом; Значение должно быть логическим
```

Пример совместного использования цепочек правил.
Значение может быть числом большим 10 или строкой с минимальной длиной 2:

```php
use Fi1a\Validation\AllOf;
use Fi1a\Validation\OneOf;

$chain = OneOf::create(
    AllOf::create()->numeric()->min(10),
    AllOf::create()->alpha()->minLength(2)
);

$result = $chain->validate(20);
echo $result->isSuccess(); // true

$result = $chain->validate('abc');
echo $result->isSuccess(); // true

$result = $chain->validate('a');
echo $result->isSuccess(); // false
echo $result->getErrors()->join("; "); // Значение не является числом; Значение должно быть минимум 10; Длина значения должна быть больше 2
```

## Проверка объекта

Вместо массива значений можно передать объект для проверки значений свойств объекта (осуществляется проверка только публичных свойств объекта):

```php
class DTO
{
    public $propertyA = 100;

    public $propertyB = 'string';

    public $propertyC;

    public $propertyD = true;

    public function getPropertyD(): bool
    {
        return $this->propertyD;
    }
}

use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make(
    new DTO(),
    [
        'propertyA' => 'required|integer',
        'propertyB' => 'required',
        'propertyC' => 'null',
        'propertyD' => 'required|boolean',
    ]
);

$result = $validation->validate();
$result->isSuccess(); // true
```

## Сообщения об ошибках

Сообщения об ошибках можно задать при создании объекта валидации с помощью метода ```make```, передав в него  массив с сообщениями,
либо позже методами ```setMessage``` или ```setMessages``` объекта валидации.
Также сообщения можно определить в наборе правил ```Fi1a\Validation\RuleSetInterface``` (возвращаемый массив метода ```getMessages```).

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make($_POST + $_FILES, [
    'firstName' => 'required|betweenLength(2, 40)',
    'lastName' => 'required|betweenLength(2, 40)',
], [
    'required' => 'Очень обязательное поле',
    'lastName|required' => '"Фамилия" обязательное поле',
]);

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("; "); // Очень обязательное поле; "Фамилия" обязательное поле
}

$validation->setMessage('firstName|required', 'Очень обязательное поле 2');
$validation->setMessage('required', '"Фамилия" обязательное поле 2');
$validation->setMessage('lastName|required', null);

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("; "); // Очень обязательное поле 2; "Фамилия" обязательное поле 2
}
```

## Заголовки полей

Заголовки полей можно задать при создании объекта валидации с помощью метода ```make```, передав в него заголовки,
либо позже методами ```setTitle``` или ```setTitles``` объекта валидации.
Также заголовки можно определить в наборе правил ```Fi1a\Validation\RuleSetInterface``` (возвращаемый массив метода ```getTitles```).

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make($_POST + $_FILES, [
    'firstName' => 'required|betweenLength(2, 40)',
    'lastName' => 'required|betweenLength(2, 40)',
], [], [
    'firstName' => 'Имя',
    'lastName' => 'Фамилия',
]);

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("; "); // Значение "Имя" является обязательным; Значение "Фамилия" является обязательным
}

$validation->setTitle('firstName', null);
$validation->setTitle('lastName', null);

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("; "); // Значение является обязательным; Значение является обязательным
}
```

## Ошибки

Сообщения об ошибках представлены коллекцией ```\Fi1a\Validation\Errors```,
которую можно получить с помощью метода ```getErrors()``` класса
результата проверки ```\Fi1a\Validation\Result```.

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make($_POST + $_FILES, [
    'id' => 'required|integer',
    'email' => 'required|email',
]);

$result = $validation->validate();

if (!$result->isSuccess()) {
    $errors = $result->getErrors(); // \Fi1a\Validation\Errors
    echo $errors->firstOfAll()->join('; ');
}
```

Доступные методы в коллекции ошибок ```\Fi1a\Validation\Errors```:

- ```firstOfAll()``` - возвращает первые ошибки для поля;
- ```allForField()``` - возвращает все ошибки для конкретного поля;
- ```firstOfField()``` - возвращает первую ошибку для конкретного поля;
- ```allForRule()``` - возвращает все ошибки для конкретного правила;
- ```asArray(bool $flat = true)``` - возвращает массив с сообщениями об ошибках
(аргумент $flat определяет в каком виде будет сформирован массив с сообщениями об ошибках).

Также доступны все методы коллекции ```\Fi1a\Collection\Collection```.

## Затронутые значения

Затронутые значения представлены коллекцией ```\Fi1a\Validation\ResultValues```,
которую можно получить с помощью метода ```getValues()``` класса результата проверки ```\Fi1a\Validation\Result```.

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make($_POST + $_FILES, [
    'id' => 'required|integer',
    'email' => 'required|email',
]);

$result = $validation->validate();

if (!$result->isSuccess()) {
    $values = $result->getValues(); // \Fi1a\Validation\ResultValues|\Fi1a\Validation\Value[]
    $values->getInvalid(); // \Fi1a\Validation\ResultValues|\Fi1a\Validation\Value[]
    $values->getValid(); // \Fi1a\Validation\ResultValues|\Fi1a\Validation\Value[]
}
```

Доступные методы в коллекции ```\Fi1a\Validation\ResultValues```:

- ```getInvalid()``` - значения не прошедшие проверку;
- ```getValid()``` - значения успешно прошедшие проверку.

Также доступны все методы коллекции ```\Fi1a\Collection\Collection```.

## Сценарии

Сценарии предназначены для определения какие правила применять к текущему состоянию. Например: при сценарии создания
пароль обязателен для заполнения, а при сценарии обновления нет. Это можно определить используя сценарии.

Класс ```Fi1a\Validation\On``` задает к какому сценарию относится цепочка правил.

| Аргумент               | Описание                                           |
|------------------------|----------------------------------------------------|
| string $fieldName      | Имя поля для которого предназначена цепочка правил |
| ?ChainInterface $chain | Цепочка правил                                     |
| string ...$scenario    | Сценарии для которых применяется цепочка правил    |

Текущий используемый сценарий передается пятым аргументом в метод `make` класса `Fi1a\Validation\Validator`.

```php
use Fi1a\Validation\AllOf;
use Fi1a\Validation\On;
use Fi1a\Validation\Validator;

$validator = new Validator();
$values = [
    'key1' => [1, 2, 3],
];
$rules = [
    'key1' => 'array',
    new On('key1', AllOf::create()->minCount(1), 'create'),
    new On('key1', AllOf::create()->minCount(4), 'update'),
    'key1:*' => 'required|integer',
];
$validation = $validator->make(
    $values,
    $rules,
    [],
    [],
    'create'
);
$result = $validation->validate();
$result->isSuccess(); // true

$validation = $validator->make(
    $values,
    $rules,
    [],
    [],
    'update'
);
$result = $validation->validate();
$result->isSuccess(); // false
```

## Набор правил

Набор правил представляет собой класс реализующий интерфейс ```\Fi1a\Validation\RuleSetInterface```.

Используя класс набора правил можно определить:
- правила для полей;
- сценарий к которому относится правило;
- заголовки полей;
- сообщения об ошибках.

Пример набора правил:

```php
use Fi1a\Validation\AbstractRuleSet;

/**
 * Набор правил
 */
class UserRuleSet extends AbstractRuleSet
{
    /**
     * @inheritDoc
     */
    public function init(): bool
    {
        $this->fields('id', 'email', 'tags', 'tags:*:id')
            ->on('create', 'copy')
            ->allOf()
            ->required();
        $this->fields('id', 'email', 'tags', 'tags:*:id')
            ->on('update')
            ->allOf()
            ->requiredIfPresence();
            
        $this->fields('id')->allOf()->integer();
        $this->fields('email')->allOf()->email();
        $this->fields('tags')->allOf()->array();
        $this->fields('tags:*:id')->allOf()->numeric();
        if ($this->getValue('password')->isPresence() || $this->getScenario() === 'create') {
            $this->fields('password')->allOf()->required()->minLength(8);
            $this->fields('confirm_password')->allOf()->required()->same('password');
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return array_merge(parent::getMessages(), [
            'tags|required' => 'Укажите хотябы один {{name}}',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitles(): array
    {
        return array_merge(parent::getTitles(), [
            'tags' => 'тег',
        ]);
    }
}
```

Использование набора правил по сценарию ```create```:

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make(new UserRuleSet($_POST + $_FILES), [], [], [], 'create');

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("\n");
}
```

## Значение присутствует для валидации (проверки)

По умолчанию большинство правил при валидации (проверке) возвращают `true`, если значение не присутсвует (за исключением `require()`).
Класс, реализующий интерфейс `Fi1a\Validation\Presence\WhenPresenceInterface`, определяет по какому критерию будет происходить
проверка на присутсвие значения.

Доступны следующие классы:

- `Fi1a\Validation\Presence\WhenPresence` - определяет присутствие значения по наличию ключа;
- `Fi1a\Validation\Presence\WhenNotValue` - определяет по переданному значению присутсвует значение или нет (если равно считается, что не присутсвует);
- `Fi1a\Validation\Presence\WhenNotNull` - определяет по значению null присутсвует значение или нет (если null считается, что не присутсвует);
- `Fi1a\Validation\Presence\WhenNotIn` - определяет по переданным значениям присутсвует значение или нет (если входит в значения считается, что не присутсвует);
- `Fi1a\Validation\Presence\WhenComposite` - используется как составной из других классов проверки присутсвия значения.

По умолчанию используется класс `Fi1a\Validation\Presence\WhenPresence`. Но вы можете передать нужный вам.

```php
use Fi1a\Validation\AllOf;
use Fi1a\Validation\Presence\WhenNotNull;

$chain = AllOf::create()->boolean(new WhenNotNull());

$chain->validate(null)->isSuccess(); // true
$chain->validate(true)->isSuccess(); // true
$chain->validate('not-boolean')->isSuccess(); // false
```

Объект, определяющий присутсвие значения, можно установить сразу для всех правил используя метод `setPresence`:

```php
use Fi1a\Validation\Presence\WhenNotNull;
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make(
    [
        'array' => [null, 2, 3],
    ],
    [
        'array' => 'array|minCount(1)',
        'array:*' => 'integer',
    ]
);

$validation->setPresence(new WhenNotNull());

$result = $validation->validate();
$result->isSuccess(); // true
```

## Правила

### alphaNumeric(?WhenPresenceInterface $presence = null)

Значение должно быть буквенно-цифровым

```php
use Fi1a\Validation\AllOf;

AllOf::create()->alphaNumeric()->validate('123abc')->isSuccess(); // true
AllOf::create()->alphaNumeric()->validate('abc 123')->isSuccess(); // false
```

### alpha(?WhenPresenceInterface $presence = null)

Является ли значение только буквенным(без чисел)

```php
use Fi1a\Validation\AllOf;

AllOf::create()->alpha()->validate('abc')->isSuccess(); // true
AllOf::create()->alpha()->validate('abc100')->isSuccess(); // false
```

### array(?WhenPresenceInterface $presence = null)

Является ли значение массивом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->array()->validate([1, 2, 3])->isSuccess(); // true
AllOf::create()->array()->validate(false)->isSuccess(); // false
```

### betweenCount(int $min, int $max, ?WhenPresenceInterface $presence = null)

Проверка на минимальное и максимальное количество элементов в массиве

```php
use Fi1a\Validation\AllOf;

AllOf::create()->betweenCount(2, 5)->validate([1, 2, 3])->isSuccess(); // true
AllOf::create()->betweenCount(2, 5)->validate(3000000)->isSuccess(); // false
AllOf::create()->betweenCount(2, 5)->validate([1,])->isSuccess(); // false
```

### betweenDate(string $minDate, string $maxDate, ?string $format = null, ?WhenPresenceInterface $presence = null)

Проверка на максимальную и минимальную дату

```php
use Fi1a\Validation\AllOf;

AllOf::create()
    ->betweenDate('10.10.2022 10:10:10', '12.10.2022 10:10:10')
    ->validate('11.10.2022 10:10:10')
    ->isSuccess(); // true

AllOf::create()
    ->betweenDate('10.10.2022 10:10:10', '12.10.2022 10:10:10')
    ->validate('10.10.2022 09:00:00')
    ->isSuccess(); // false

AllOf::create()
    ->betweenDate('10.10.2022', '12.10.2022', 'd.m.Y')
    ->validate('11.10.2022')
    ->isSuccess(); // true
```

### betweenLength(int $min, int $max, ?WhenPresenceInterface $presence = null)

Проверка на максимальную и минимальную длину строки

```php
use Fi1a\Validation\AllOf;

AllOf::create()->betweenLength(2, 5)->validate(150)->isSuccess(); // true
AllOf::create()->betweenLength(2, 5)->validate(3000000)->isSuccess(); // false
AllOf::create()->betweenLength(2, 5)->validate('abc def gh')->isSuccess(); // false
```

### between($min, $max, ?WhenPresenceInterface $presence = null)

Проверка на максимальное и мимальное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->between(100, 200)->validate(150)->isSuccess(); // true
AllOf::create()->between(100, 200)->validate(300)->isSuccess(); // false
AllOf::create()->between(100, 200)->validate('abc')->isSuccess(); // false
```

### boolean(?WhenPresenceInterface $presence = null)

Является ли значение логическим

```php
use Fi1a\Validation\AllOf;

AllOf::create()->boolean()->validate(true)->isSuccess(); // true
AllOf::create()->boolean()->validate(false)->isSuccess(); // true
AllOf::create()->boolean()->validate('TRUE')->isSuccess(); // true
AllOf::create()->boolean()->validate('FALSE')->isSuccess(); // true
AllOf::create()->boolean()->validate('0')->isSuccess(); // true
AllOf::create()->boolean()->validate('1')->isSuccess(); // true
AllOf::create()->boolean()->validate(0)->isSuccess(); // true
AllOf::create()->boolean()->validate(1)->isSuccess(); // true
AllOf::create()->boolean()->validate('Y')->isSuccess(); // true
AllOf::create()->boolean()->validate('N')->isSuccess(); // true
AllOf::create()->boolean()->validate(100)->isSuccess(); // false
AllOf::create()->boolean()->validate('abc')->isSuccess(); // false
```

### date(string $format = null, ?WhenPresenceInterface $presence = null)

Проверка на формат даты

```php
use Fi1a\Validation\AllOf;

AllOf::create()->date()->validate('10.10.2022')->isSuccess(); // true
AllOf::create()->date('d')->validate('10.10.2022')->isSuccess(); // false
AllOf::create()->date('d m, Y')->validate('10 10, 2022')->isSuccess(); // true
AllOf::create()->date()->validate('abc')->isSuccess(); // false
```

### email(?WhenPresenceInterface $presence = null)

Является ли значение email адресом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->email()->validate('foo@bar.ru')->isSuccess(); // true
AllOf::create()->email()->validate('foo')->isSuccess(); // false
```

### equalDate(string $equalDate, ?string $format = null, ?WhenPresenceInterface $presence = null)

Проверяет дату на равенство

```php
use Fi1a\Validation\AllOf;

AllOf::create()->equalDate('10.10.2022 10:10:10')->validate('10.10.2022 10:10:10')->isSuccess(); // true
AllOf::create()->equalDate('10.10.2022 10:10:10')->validate('10.10.2022 09:00:00')->isSuccess(); // false
AllOf::create()->equalDate('10.10.2022', 'd.m.Y')->validate('10.10.2022')->isSuccess(); // true
```

### equal(float $equal, ?WhenPresenceInterface $presence = null)

Проверяет число на равенство

```php
use Fi1a\Validation\AllOf;

AllOf::create()->equal(100)->validate(100)->isSuccess(); // true
AllOf::create()->equal(100)->validate(200)->isSuccess(); // false
```

### fileSize(string $min, string $max, ?WhenPresenceInterface $presence = null)

Размер загруженного файла.

Указатели на размер:
- B - байты;
- KB, K - килобайты;
- MB, M - мегабайты;
- GB, G - гигабайты;
- TB, T - террабайты;
- PB, P - петабайты.

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make($_POST + $_FILES, [
    'photo' => 'required|fileSize("0", "1MB")',
]);

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("\n");
}
```

### generic(array $rules, array $messages = [], array $titles = [], ?WhenPresenceInterface $presence = null)

Вложенные правила

```php
use Fi1a\Validation\Validator;

$validator = new Validator();
$validation = $validator->make(
    [
        'columns' => [
            [
                'foo' => null,
            ],
            [
                'foo' => [
                    'bar' => 'baz'
                ],
            ],
        ],
    ],
    [
        'columns' => AllOf::create()->array(),
        'columns:*:foo' => AllOf::create()->generic(['bar' => 'required']),
    ]
);
$validation->validate()->isSuccess(); // true

$validator = new Validator();
$validation = $validator->make(
    [
        'columns' => [
            [
                'foo' => [],
            ],
            [
                'foo' => [
                    'bar' => 'baz'
                ],
            ],
        ],
    ],
    [
        'columns' => AllOf::create()->array(),
        'columns:*:foo' => AllOf::create()->generic(['bar' => 'required']),
    ]
);
$validation->validate()->isSuccess(); // false
```

### url(?WhenPresenceInterface $presence = null)

Валидация (проверка) url адреса

```php
use Fi1a\Validation\AllOf;

AllOf::create()->url()->validate('https://domain.ru/path/')->isSuccess(); // true
AllOf::create()->url()->validate('https')->isSuccess(); // false
```

### in($presence, ...$in)

Допустимые значения (не строгая проверка значения)

```php
use Fi1a\Validation\AllOf;

AllOf::create()->in(1, 2, 3)->validate(1)->isSuccess(); // true
AllOf::create()->in(1, 2, 3)->validate(100.1)->isSuccess(); // false
AllOf::create()->in('camelCase', 'UPPERCASE')->validate('uppercase')->isSuccess(); // true
```

### integer(?WhenPresenceInterface $presence = null)

Является ли значение целым числом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->integer()->validate(1)->isSuccess(); // true
AllOf::create()->integer()->validate(100.1)->isSuccess(); // false
```

### json(?WhenPresenceInterface $presence = null)

Является ли значение json-строкой

```php
use Fi1a\Validation\AllOf;

AllOf::create()->json()->validate(json_encode([1, 2, 3]))->isSuccess(); // true
AllOf::create()->json()->validate('{')->isSuccess(); // false
```

### maxCount(int $max, ?WhenPresenceInterface $presence = null)

Проверка на максимальное количество элементов в массиве

```php
use Fi1a\Validation\AllOf;

AllOf::create()->maxCount(2)->validate([1,])->isSuccess(); // true
AllOf::create()->maxCount(2)->validate(100)->isSuccess(); // false
AllOf::create()->maxCount(2)->validate([1, 2, 3])->isSuccess(); // false
```

### maxDate(string $maxDate, ?string $format = null, ?WhenPresenceInterface $presence = null)

Проверка на максимальную дату

```php
use Fi1a\Validation\AllOf;

AllOf::create()->maxDate('10.10.2022 11:10:10')->validate('10.10.2022 10:10:10')->isSuccess(); // true
AllOf::create()->maxDate('10.10.2022 10:10:10')->validate('10.10.2022 12:00:00')->isSuccess(); // false
AllOf::create()->maxDate('10.10.2022', 'd.m.Y')->validate('09.10.2022')->isSuccess(); // true
```

### maxLength(int $max, ?WhenPresenceInterface $presence = null)

Проверка на максимальную длину строки

```php
use Fi1a\Validation\AllOf;

AllOf::create()->maxLength(5)->validate('123')->isSuccess(); // true
AllOf::create()->maxLength(5)->validate(123)->isSuccess(); // true
AllOf::create()->maxLength(5)->validate(1000000)->isSuccess(); // false
AllOf::create()->maxLength(5)->validate('abc def h')->isSuccess(); // false
```

### max($max, ?WhenPresenceInterface $presence = null)

Проверка на максимальное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->max(100)->validate(50)->isSuccess(); // true
AllOf::create()->max(200)->validate(300)->isSuccess(); // false
AllOf::create()->max(200)->validate('abc')->isSuccess(); // false
```

### mime(WhenPresenceInterface|string $presence, string ...$extensions)

Тип загруженного файла

```php
use Fi1a\Validation\Validator;

$validator = new Validator();

$validation = $validator->make($_POST + $_FILES, [
    'photo' => 'fileSize("0", "1MB")|mime("jpeg", "png")',
]);

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("\n");
}
````

### minCount(int $min, ?WhenPresenceInterface $presence = null)

Проверка на минимальное количество элементов в массиве

```php
use Fi1a\Validation\AllOf;

AllOf::create()->minCount(2)->validate([1, 2, 3])->isSuccess(); // true
AllOf::create()->minCount(2)->validate(100)->isSuccess(); // false
AllOf::create()->minCount(2)->validate([1])->isSuccess(); // false
```

### minDate(string $minDate, ?string $format = null, ?WhenPresenceInterface $presence = null)

Проверка на минимальную дату

```php
use Fi1a\Validation\AllOf;

AllOf::create()->minDate('10.10.2022 10:10:10')->validate('10.10.2022 10:10:10')->isSuccess(); // true
AllOf::create()->minDate('10.10.2022 10:10:10')->validate('10.10.2022 09:00:00')->isSuccess(); // false
AllOf::create()->minDate('10.10.2022', 'd.m.Y')->validate('10.10.2022')->isSuccess(); // true
```

### minLength(int $min, ?WhenPresenceInterface $presence = null)

Проверка на минимальную длину строки

```php
use Fi1a\Validation\AllOf;

AllOf::create()->minLength(5)->validate('123456')->isSuccess(); // true
AllOf::create()->minLength(5)->validate(123456)->isSuccess(); // true
AllOf::create()->minLength(5)->validate(100)->isSuccess(); // false
AllOf::create()->minLength(5)->validate('abc')->isSuccess(); // false
```

### min($min, ?WhenPresenceInterface $presence = null)

Проверка на минимальное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->min(100)->validate(200)->isSuccess(); // true
AllOf::create()->min(200)->validate(100)->isSuccess(); // false
AllOf::create()->min(200)->validate('abc')->isSuccess(); // false
```

### notIn($presence, ...$notIn)

Не допустимые значения (не строгая проверка значения)

```php
use Fi1a\Validation\AllOf;

AllOf::create()->notIn(1, 2, 3)->validate(4)->isSuccess(); // true
AllOf::create()->notIn(1, 2, 3)->validate(2)->isSuccess(); // false
AllOf::create()->notIn('camelCase', 'UPPERCASE')->validate('uppercase')->isSuccess(); // false
```

### null(?WhenPresenceInterface $presence = null)

Является ли значение null

```php
use Fi1a\Validation\AllOf;

AllOf::create()->null()->validate(null)->isSuccess(); // true
AllOf::create()->null()->validate(false)->isSuccess(); // false
```

### numeric(?WhenPresenceInterface $presence = null)

Является ли значение числом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->numeric()->validate(1)->isSuccess(); // true
AllOf::create()->numeric()->validate(false)->isSuccess(); // false
```

### regex(string $regex, ?WhenPresenceInterface $presence = null)

Проверка на регулярное выражение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->regex('/[0-9]/mui')->validate(200)->isSuccess(); // true
AllOf::create()->regex('/[0-9]/mui')->validate('abc')->isSuccess(); // false
```

### requiredIfPresence(?WhenPresenceInterface $presence = null)

Обязательное значение, если передано

```php
use Fi1a\Validation\Validator;

$validator = new Validator();
$validation = $validator->make(['foo' => true], ['foo' => 'requiredIfPresence']);

$validation->validate()->isSuccess(); // true

$validation->setValues(['foo' => null]);
$validation->validate()->isSuccess(); // false

$validation->setValues([]);
$validation->validate()->isSuccess(); // true
```

### required()

Обязательное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->required()->validate(true)->isSuccess(); // true
AllOf::create()->required()->validate(null)->isSuccess(); // false
```

### requiredWith(string ...$fieldNames)

Обязательное значение, если есть значения в полях

```php
use Fi1a\Validation\AllOf;

AllOf::create()
    ->requiredWith('array:foo', 'array:bar')
    ->validate(['array' => ['foo' => 'foo', 'bar' => 'bar'], 'baz' => 'baz'], 'baz')
    ->isSuccess(); // true
                
AllOf::create()
    ->requiredWith('array:foo', 'array:bar')
    ->validate(['array' => ['foo' => 'foo', 'bar' => null], 'baz' => null], 'baz')
    ->isSuccess(); // true

AllOf::create()
    ->requiredWith('array:foo', 'array:bar')
    ->validate(['array' => ['foo' => 'foo', 'bar' => 'bar'], 'baz' => null], 'baz')
    ->isSuccess(); // false
```

### same(string $fieldName, ?WhenPresenceInterface $presence = null)

Совпадает ли значение со значением в указанном поле

```php
use Fi1a\Validation\AllOf;

AllOf::create()->same('field1')->validate(200)->isSuccess(); // false
AllOf::create()->same('bar')->validate(['foo' => 200, 'bar' => 200], 'foo')->isSuccess(); // true
```

### strictIn($presence, ...$in)

Допустимые значения (строгая проверка значения)

```php
use Fi1a\Validation\AllOf;

AllOf::create()->strictIn(1, 2, 3)->validate(1)->isSuccess(); // true
AllOf::create()->strictIn(1, 2, 3)->validate(100.1)->isSuccess(); // false
AllOf::create()->strictIn('camelCase', 'UPPERCASE')->validate('uppercase')->isSuccess(); // false
```

### strictNotIn($presence, ...$notIn)

Не допустимые значения (строгая проверка значения)

```php
use Fi1a\Validation\AllOf;

AllOf::create()->strictNotIn(1, 2, 3)->validate(4)->isSuccess(); // true
AllOf::create()->strictNotIn(1, 2, 3)->validate(2)->isSuccess(); // false
AllOf::create()->strictNotIn('camelCase', 'UPPERCASE')->validate('uppercase')->isSuccess(); // true
```

### string(?WhenPresenceInterface $presence = null)

Является ли значение строкой

```php
use Fi1a\Validation\AllOf;

AllOf::create()->string()->validate('foo')->isSuccess(); // true
AllOf::create()->string()->validate(false)->isSuccess(); // false
```

## Пользовательское правило проверки

В библиотеки есть возможность расширить доступные правила проверки.
Правило проверки должно реализовывать интерфейс ```\Fi1a\Validation\Rule\RuleInterface```.

Пример реализации пользовательского правила проверки:

```php
use \Fi1a\Validation\Rule\AbstractRule;
use \Fi1a\Validation\ValueInterface;

/**
 * Проверка на уникальное значение
 */
class UniqueRule extends AbstractRule
{
    /**
     * @var Bitrix\Main\ORM\Data\DataManager
     */
    private $className;

    /**
     * @var string
     */
    private $column;

    /**
     * @var int|null
     */
    private $notId;

    /**
     * Конструктор
     */
    public function __construct(string $className, string $column, ?int $notId = null, ?WhenPresenceInterface $presence = null)
    {
        $this->className = $className;
        $this->column = $column;
        $this->notId = $notId;
        parent::__construct($presence);
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }
        $filter = [
            $this->column => $value->getValue(),
        ];
        if ($this->notId) {
            $filter['!ID'] = $this->notId;
        }
        $success = $this->className::getCount($filter) === 0;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}не является уникальным', 'unique');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'unique';
    }
}
```

Зарегистрируем пользовательское правило в классе валидатора с помощью метода ```addRule```:

```php
\Fi1a\Validation\Validator::addRule(UniqueRule::class);
```

И можно использовать пользовательское правило проверки:

```php
use \Fi1a\Validation\AllOf;
use \Bitrix\Main\UserTable;

$unique = AllOf::create()->unique(UserTable::class, 'LOGIN');
$unique->validate('admin')->isSuccess(); // false
$unique->validate('user')->isSuccess(); // true
```

[badge-release]: https://img.shields.io/packagist/v/fi1a/validation?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/validation?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/validation?style=flat-square
[badge-coverage]: https://img.shields.io/badge/coverage-100%25-green
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/validation.svg?style=flat-square&colorB=mediumvioletred
[badge-mail]: https://img.shields.io/badge/mail-support%40fi1a.ru-brightgreen

[packagist]: https://packagist.org/packages/fi1a/validation
[license]: https://github.com/fi1a/validation/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/validation
[mail]: mailto:support@fi1a.ru