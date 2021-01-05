<?php

require_once "VerificationClass.php";

class ReportClass extends Verify
{
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $passw;
    protected $charset;
    
    protected $catName;
    
    public function __construct()
    {
        require_once "pdoshop.php";
      
        $this->catName = htmlspecialchars($_POST['name1']);
    }
  
    ///////////////////////////////////
    
    protected function verification()
    {
        $this->requiredFieldsVerification();
        $this->verifyNumber($this->catName);
    }
    
    ///////////////////////////////////
    
    protected function formData()
    {
        $dsn = "mysql:host=$this->hostname;dbname=$this->dbname;charset=$this->charset";
        $pdoVar = new PDO($dsn, $this->username, $this->passw);
        
        $statement = $pdoVar->prepare("SELECT NumberCat, NameCat FROM CategoryT WHERE NumberCat = :number");
        $statement->bindValue(':number', $this->catName);
        $statement->execute();
        
        $stroka = $statement->fetch();
        if ($stroka !== false) {
            /* Данная категория существует, формируем отчёт */
            $statement2 = $pdoVar->prepare("SELECT NameGood, PriceGood, QuantityGood FROM GoodsT WHERE NumberCat = :number2");
            $statement2->bindValue(':number2', $stroka['NumberCat']);
            $statement2->execute();
            
            // Ширина отчёта на листе бумаги формата А4 - примерно 600 пикселей
            // Вычислим: какая необходима высота отчёта? Для этого посмотрим, сколько товаров данной категории вернул запрос
            
            // Число строк в таблице GoodsT, удовлетворяющих запросу; вспомогательный запрос
            $statementHelping = $pdoVar->prepare('SELECT COUNT(*) FROM GoodsT WHERE NumberCat = :number2');
            $statementHelping->bindValue(':number2', $stroka['NumberCat']);
            $statementHelping->execute();
            // Делаем приведение типа, так как fetchColumn возвращает тип string
            $chislostrok = (int) ($statementHelping->fetchColumn());
            
            // На каждый товар выделим по высоте отчёта по 30 пикселей + 40 пикселей в строке заглавия таблицы + шапка отчёта и подвал отчёта - по 70 пикселей
            $width = 600;
            $height = 180 + $chislostrok * 30;
            
            $im = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($im, 255, 255, 255);
            imagefill($im, 0, 0, $white);
            $black = imagecolorallocate($im, 0, 0, 0);
            
            // Шрифты скопированы из /usr/share/fonts/truetype/dejavu/
            
            imagettftext($im, 14, 0, 140, 30, $black, "DejaVuSans-Bold.ttf", "ОТЧЁТ О НАЛИЧИИ ТОВАРОВ");
            
            $text1 = "Категория товара: ".$stroka['NumberCat']." - ".$stroka['NameCat'];
            imagettftext($im, 11, 0, 35, 60, $black, "DejaVuSans.ttf", $text1);
            
            imagettftext($im, 10, 0, 20, 90, $black, "DejaVuSans-Bold.ttf", "№п/п");
            imagettftext($im, 10, 0, 80, 90, $black, "DejaVuSans-Bold.ttf", "Название товара");
            imagettftext($im, 10, 0, 360, 90, $black, "DejaVuSans-Bold.ttf", "Цена");
            imagettftext($im, 10, 0, 460, 90, $black, "DejaVuSans-Bold.ttf", "Количество");
            
            // Рисуем таблицу
            // Прежде всего установим толщину линии в пикселах
            imagesetthickness($im, 2);
            
            imageline($im, 15, 75, 580, 75, $black);
            imageline($im, 15, 100, 580, 100, $black);
            
            imageline($im, 70, 75, 70, 100, $black);
            imageline($im, 350, 75, 350, 100, $black);
            imageline($im, 450, 75, 450, 100, $black);
            
            $fullPrice = 0.00; // Полная стоимость товаров данной категории
            $i = 1; // Счётчик строк в отчёте
            
            imagesetthickness($im, 1);
            
            while (($stroka2 = $statement2->fetch()) !== false) {
                $fullPrice += ($stroka2['PriceGood'] * $stroka2['QuantityGood']);
                
                $text1 = "$i";
                imagettftext($im, 9, 0, 20, 95 + $i*25, $black, "DejaVuSans.ttf", $text1);
                $text1 = $stroka2['NameGood'];
                imagettftext($im, 9, 0, 80, 95 + $i*25, $black, "DejaVuSans.ttf", $text1);
                $text1 = $stroka2['PriceGood'];
                imagettftext($im, 9, 0, 360, 95 + $i*25, $black, "DejaVuSans.ttf", $text1);
                $text1 = $stroka2['QuantityGood'];
                imagettftext($im, 9, 0, 460, 95 + $i*25, $black, "DejaVuSans.ttf", $text1);
              
                imageline($im, 70, 75 + $i*25, 70, 100 + $i*25, $black);
                imageline($im, 350, 75 + $i*25, 350, 100 + $i*25, $black);
                imageline($im, 450, 75 + $i*25, 450, 100 + $i*25, $black);
              
                $i++;
            }
            
            imagettftext($im, 11, 0, 200, 105 + $i*25, $black, "DejaVuSans-Bold.ttf", "Общая стоимость товаров: ");
            imagettftext($im, 11, 0, 450, 105 + $i*25, $black, "DejaVuSans-Bold.ttf", $fullPrice);
            
            imageline($im, 440, 110 + $i*25, 550, 110 + $i*25, $black);
            
            $text1 = date("d-m-Y H:i:s");
            imagettftext($im, 9, 0, 30, 135 + $i*25, $black, "DejaVuSans.ttf", $text1);
            imagettftext($im, 8, 0, 60, 150 + $i*25, $black, "DejaVuSans.ttf", "(дата)");
            
            imagettftext($im, 8, 0, 460, 150 + $i*25, $black, "DejaVuSans.ttf", "(подпись)");
            
            imageline($im, 20, 140 + $i*25, 200, 140 + $i*25, $black);
            imageline($im, 420, 140 + $i*25, 550, 140 + $i*25, $black);
      
            // Вывести изображение
            header('Content-type: image/png');
            imagepng($im);
             
            // Очистить ресурсы
            imagedestroy($im);
              
            /* Окончание формирования отчёта; завершение выполнения данной программы */
            $pdoVar = null;
        } else {
            echo "Данная категория товаров отсутствует в базе магазина";
            $pdoVar = null;
            exit;
        }
    }
      
    ///////////////////////////////////
    
    // Проверка введённых в форме авторизации данных
    public function verFunction()
    {
        // Проверим на ошибки введённые данные
        $this->verification();
          
        // Проверим введённый номер категории на существование его в базе, и если существует, то формируем данные отчёта
        $this->formData();
    }
}

$var = new ReportClass();
$var->verFunction();
