# Библиотека проверки (валидации) значений

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]

## Функции

- Проверка массива и отдельного значения;
- Поддержка набора правил со сценариями;
- Возможность изменить названия полей;
- Возможность изменить текст ошибки;
- Возможность расширить пользовательскими правилами проверки;

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/validation ">1.0.0 <=1.1.0"
```

**Внимание!** Совместимость версий не гарантируется при переходе major или minor версии.
Указывайте допустимую версию пакета в своем проекте следующим образом: ```"fi1a/validation": ">=1.0.0 <1.1.0"```.

## Использование

### Проверка массива полей

Для проверки массива полей нужно использовать метод ```make``` класса валидатора ```\Fi1a\Validation\Validator```.
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

### Проверка одного поля

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

### Сообщения об ошибках

Сообщения об ошибках можно задать при создании объекта валидации с помощью метода ```make``` передав в него  массив с сообщениями,
либо позже методами ```setMessage``` или ```setMessages``` объекта валидации.
Также сообщения можно определить в наборе правил (возвращаемый массив метода ```getMessages```).

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

### Заголовки полей

Заголовки полей можно задать при создании объекта валидации с помощью метода ```make``` передав в него заголовки,
либо позже методами ```setTitle``` или ```setTitles``` объекта валидации.
Также заголовки можно определить в наборе правил (возвращаемый массив метода ```getTitles```).

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

### Набор правил

Набор правил представляет собой класс реализующий интерфейс ```\Fi1a\Validation\RuleSetInterface```.

Используя класс набора правил можно определить:
- правила для полей;
- сценарий к которому относится правило;
- заголовки полей;
- сообщения об ошибках.

Пример набора правил:

```php
/**
 * Набор правил
 */
class UserRuleSet extends \Fi1a\Validation\AbstractRuleSet
{
    /**
     * @inheritDoc
     */
    public function init(): bool
    {
        $this->fields('id', 'email', 'password', 'confirm_password', 'tags', 'tags:*:id')
            ->on('create')
            ->allOf()
            ->required();

        $this->fields('id')->allOf()->integer();
        $this->fields('email')->allOf()->email();
        $this->fields('password')->allOf()->minLength(8);
        $this->fields('confirm_password')->allOf()->same('password');
        $this->fields('tags')->allOf()->array();
        $this->fields('tags:*:id')->allOf()->numeric();

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

$validation = $validator->make(new UserRuleSet($_POST + $_FILES, 'create'));

$result = $validation->validate();

if (!$result->isSuccess()) {
    echo $result->getErrors()->join("\n");
}
```

### Правила

#### alphaNumeric()

Значение должно быть буквенно-цифровым

```php
use Fi1a\Validation\AllOf;

AllOf::create()->alphaNumeric()->validate('123abc')->isSuccess(); // true
AllOf::create()->alphaNumeric()->validate('abc 123')->isSuccess(); // false
```

#### alpha()

Является ли значение только буквенным(без чисел)

```php
use Fi1a\Validation\AllOf;

AllOf::create()->alpha()->validate('abc')->isSuccess(); // true
AllOf::create()->alpha()->validate('abc100')->isSuccess(); // false
```

#### array()

Является ли значение массивом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->array()->validate([1, 2, 3])->isSuccess(); // true
AllOf::create()->array()->validate(false)->isSuccess(); // false
```

#### betweenCount(int $min, int $max)

Проверка на минимальное и максимальное количество элементов в массиве

```php
use Fi1a\Validation\AllOf;

AllOf::create()->betweenCount(2, 5)->validate([1, 2, 3])->isSuccess(); // true
AllOf::create()->betweenCount(2, 5)->validate(3000000)->isSuccess(); // false
AllOf::create()->betweenCount(2, 5)->validate([1,])->isSuccess(); // false
```

#### betweenLength(int $min, int $max)

Проверка на максимальную и минимальную длину строки

```php
use Fi1a\Validation\AllOf;

AllOf::create()->betweenLength(2, 5)->validate(150)->isSuccess(); // true
AllOf::create()->betweenLength(2, 5)->validate(3000000)->isSuccess(); // false
AllOf::create()->betweenLength(2, 5)->validate('abc def gh')->isSuccess(); // false
```

#### between($min, $max)

Проверка на максимальное и мимальное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->between(100, 200)->validate(150)->isSuccess(); // true
AllOf::create()->between(100, 200)->validate(300)->isSuccess(); // false
AllOf::create()->between(100, 200)->validate('abc')->isSuccess(); // false
```

#### boolean()

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

#### date(string $format = null)

Проверка на формат даты

```php
use Fi1a\Validation\AllOf;

AllOf::create()->date()->validate('10.10.2022')->isSuccess(); // true
AllOf::create()->date('d')->validate('10.10.2022')->isSuccess(); // false
AllOf::create()->date()->validate('abc')->isSuccess(); // false
```

#### email()

Является ли значение email адресом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->email()->validate('foo@bar.ru')->isSuccess(); // true
AllOf::create()->email()->validate('foo')->isSuccess(); // false
```

#### in(...$in)

Допустимые значения

```php
use Fi1a\Validation\AllOf;

AllOf::create()->in(1, 2, 3)->validate(1)->isSuccess(); // true
AllOf::create()->in(1, 2, 3)->validate(100.1)->isSuccess(); // false
```

#### integer()

Является ли значение целым числом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->integer()->validate(1)->isSuccess(); // true
AllOf::create()->integer()->validate(100.1)->isSuccess(); // false
```

#### json()

Является ли значение json строкой

```php
use Fi1a\Validation\AllOf;

AllOf::create()->json()->validate(json_encode([1, 2, 3]))->isSuccess(); // true
AllOf::create()->json()->validate('{')->isSuccess(); // false
```

#### maxCount(int $max)

Проверка на максимальное количество элементов в массиве

```php
use Fi1a\Validation\AllOf;

AllOf::create()->maxCount(2)->validate([1,])->isSuccess(); // true
AllOf::create()->maxCount(2)->validate(100)->isSuccess(); // false
AllOf::create()->maxCount(2)->validate([1, 2, 3])->isSuccess(); // false
```

#### maxLength(int $max)

Проверка на максимальную длину строки

```php
use Fi1a\Validation\AllOf;

AllOf::create()->maxLength(5)->validate('123')->isSuccess(); // true
AllOf::create()->maxLength(5)->validate(123)->isSuccess(); // true
AllOf::create()->maxLength(5)->validate(1000000)->isSuccess(); // false
AllOf::create()->maxLength(5)->validate('abc def h')->isSuccess(); // false
```

#### max($max)

Проверка на максимальное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->max(100)->validate(50)->isSuccess(); // true
AllOf::create()->max(200)->validate(300)->isSuccess(); // false
AllOf::create()->max(200)->validate('abc')->isSuccess(); // false
```

#### minCount(int $min)

Проверка на минимальное количество элементов в массиве

```php
use Fi1a\Validation\AllOf;

AllOf::create()->minCount(2)->validate([1, 2, 3])->isSuccess(); // true
AllOf::create()->minCount(2)->validate(100)->isSuccess(); // false
AllOf::create()->minCount(2)->validate([1])->isSuccess(); // false
```

#### minLength(int $min)

Проверка на минимальную длину строки

```php
use Fi1a\Validation\AllOf;

AllOf::create()->minLength(5)->validate('123456')->isSuccess(); // true
AllOf::create()->minLength(5)->validate(123456)->isSuccess(); // true
AllOf::create()->minLength(5)->validate(100)->isSuccess(); // false
AllOf::create()->minLength(5)->validate('abc')->isSuccess(); // false
```

#### min($min)

Проверка на минимальное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->min(100)->validate(200)->isSuccess(); // true
AllOf::create()->min(200)->validate(100)->isSuccess(); // false
AllOf::create()->min(200)->validate('abc')->isSuccess(); // false
```

#### notIn(...$notIn)

Не допустимые значения

```php
use Fi1a\Validation\AllOf;

AllOf::create()->notIn(1, 2, 3)->validate(4)->isSuccess(); // true
AllOf::create()->notIn(1, 2, 3)->validate(2)->isSuccess(); // false
```

#### null()

Является ли значение null

```php
use Fi1a\Validation\AllOf;

AllOf::create()->null()->validate(null)->isSuccess(); // true
AllOf::create()->null()->validate(false)->isSuccess(); // false
```

#### numeric()

Является ли значение числом

```php
use Fi1a\Validation\AllOf;

AllOf::create()->numeric()->validate(1)->isSuccess(); // true
AllOf::create()->numeric()->validate(false)->isSuccess(); // false
```

#### regex(string $regex)

Проверка на регулярное выражение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->regex('/[0-9]/mui')->validate(200)->isSuccess(); // true
AllOf::create()->regex('/[0-9]/mui')->validate('abc')->isSuccess(); // false
```

#### required()

Обязательное значение

```php
use Fi1a\Validation\AllOf;

AllOf::create()->required()->validate(true)->isSuccess(); // true
AllOf::create()->required()->validate(null)->isSuccess(); // false
```

#### same(string $fieldName)

Совпадают ли значения с указанным полем

```php
use Fi1a\Validation\AllOf;

AllOf::create()->same('field1')->validate(200)->isSuccess(); // false
AllOf::create()->same('bar')->validate(['foo' => 200, 'bar' => 200], 'foo')->isSuccess(); // true
```

### Пользовательское правило проверки

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
    public function __construct(string $className, string $column, ?int $notId = null)
    {
        $this->className = $className;
        $this->column = $column;
        $this->notId = $notId;
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

[packagist]: https://packagist.org/packages/fi1a/validation
[license]: https://github.com/fi1a/validation/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/validation