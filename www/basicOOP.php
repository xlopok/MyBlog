<?php


class Cat {
    private $name;
    public $color;
    public $weight;

    public function __construct(string $name)
    {
        $this->name =$name;
    }

    public function sayHello() {
        echo 'Привет, меня зовут '. $this->name. '.';
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}

$cat1 = new Cat('kittycat');

////$cat1->setName('Снежочек мой');
//echo '<br>';
////$cat1->sayHello();
//echo $cat1->getName();


class Post {
    protected  $title;
    protected  $text;

    public function __construct(string $title, string $text)
    {
        $this->title= $title;
        $this->text= $text;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function  getText()
    {
        return $this->text;
    }

    public function setText($text): void
    {
        $this->title = $text;
    }
}

class Lesson extends Post {
    protected $homework;

    public function __construct(string $title, string $text, string $homework)
    {
        parent::__construct($title, $text);
        $this->homework = $homework;
    }

    public function getHomework(): string
    {
        return $this->homework;
    }

    public function setHomework(string $homework): void
    {
        $this->homework = $homework;
    }
}

//$lesson = new Lesson('Очкодинозавра', 'Твоя мамаша толстая', 'Купить калготки');
//echo 'Название урока: ' . $lesson->getTitle();

class PaidLesson extends Lesson {

    protected $price;

    public function __construct(string $title, string $text, string $homework, int $price)
    {
        parent::__construct($title, $text, $homework);
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;
    }
}

//echo '<br>';
//
//$priceObject = new PaidLesson('Урок о наследовании в PHP', 'ЛОЛКЕКЧЕБУРЕК', "Ложитесь спать, утро вечера мудренее", 99.90);
//var_dump($priceObject);
interface CalculateSquare
{
    public function calculateSquare(): float;
}

class Rectangle implements CalculateSquare
{
    private $x;
    private $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function calculateSquare(): float
    {
        return $this->x * $this->y;
    }
}

class Square implements CalculateSquare
{
    private $x;

    public function __construct(float $x)
    {
        $this->x = $x;
    }

    public function calculateSquare(): float
    {
        return $this->x ** 2;
    }
}

class Circle implements CalculateSquare
{
    const PI =  3.1416;
    private $r;

    public function __construct(float $r)
    {
        $this->r = $r;
    }

    public function calculateSquare(): float
    {
//        self::PI or Circle::PI;
        return  self::PI * ($this->r ** 2);
    }
}

//$circle1 = new Circle(3);
////echo $circle->calculateSquare();
//var_dump($circle1 instanceof Circle);

//$objects = [
//    new Square(5),
//    new Rectangle(2, 4),
//    new Circle(5)
//];
//
//foreach ($objects as $object) {
//    if($object instanceof CalculateSquare) {
//        echo 'Объект класса '.get_class($object) .' реализует интерфейс CalculateSquare. Площадь: ' . $object->calculateSquare();
//        echo '<br>';
//    }
//    else {
//
//        echo 'Объект класса ' . get_class($object) . ' не реализует интерфейс CalculateSquare';
//        echo '<br>';
//    }
////}
///
///
///
///
//interface ISayYourClass
//{
//    public function sayYourClass(): string;
//}
//
//trait SayYourClassTrait
//{
//    public function sayYourClass(): string
//    {
//        return 'My class is ' . self::class;
//    }
//}
//
//
//class Man implements ISayYourClass
//{
//    use SayYourClassTrait;
//}
//
//class Box implements ISayYourClass
//{
//   use SayYourClassTrait;
//}
//
//$man = new Man();
//$box = new Box();
//
//echo $man->sayYourClass();
//echo '<br>';
//echo $box->sayYourClass();


abstract class Human
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;

    public function introduceYourself() {
        return $this->getGreetings() . '!' . $this->getMyNameIs() . ' ' . $this->getName();
    }
}


class RussianHuman extends Human
{
    public function getMyNameIs(): string {
        return "Меня зовут";
    }

    public function getGreetings(): string {
        return 'Привет';
    }
}

class EnglishHuman extends Human
{
    public function getMyNameIs(): string {
        return "My name is";
    }

    public function getGreetings(): string {
        return 'Hello';
    }
}

$russianHuman = new RussianHuman('Иван');
$englishHuman = new EnglishHuman('John');

//echo $russianHuman->introduceYourself();
//echo '<br>';
//echo $englishHuman->introduceYourself();

class User
{
    private $role;
    private $name;

    public function __construct(string $role, string $name)
    {
        $this->role = $role;
        $this->name = $name;
    }

    public static function crateAdmin(string $name) {
        return new self('admin', $name);
    }
}

$admin = User::crateAdmin('Ivan');
$admin2 = new User('admin', 'Vasily');

//var_dump($admin);
//echo '<br>';
//var_dump($admin2);


class Human1
{
    private static $count = 0;

    public function __construct()
    {
        self::$count++;
    }

    public static function getCount(): int
    {
        return self::$count;
    }
}

$human1 = new Human1();
//$human2 = new Human1();
//$human3 = new Human1();
//echo 'Людей ужe ' . Human1::getCount();