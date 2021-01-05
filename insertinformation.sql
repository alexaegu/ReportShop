USE SHOPBASE;

INSERT INTO `CategoryT` (`NameCat`) VALUES
('Книги'),
('Разная одежда'),
('Продукты питания'),
('Техника');

INSERT INTO `GoodsT` (`NumberCat`, `NameGood`, `PriceGood`, `QuantityGood`, `PermittedQuantity`) VALUES
(2, 'Платье женское', 200.38, 49, 2),
(2, 'Рубашка мужская', 117.00, 26, 1),
(1, 'Windows 3.11 для рабочих групп', 96.12, 900, 7),
(4, 'Пейджер', 100.09, 4, 21),
(4, 'Смартфон Samsung', 990.99, 19, 3),
(4, 'Мышь компьютерная с ковриком', 27.16, 178, 4);

INSERT INTO `UsersT` (`LoginUser`, `PasswordUser`, `EmailUser`) VALUES
('romashka12', '$2y$10$awSqX768ZuDKKJ3OSaDX2uSwadCk3UMT1pP0V18D370dExMCqPvJW', 'romka@site.com'),
('listik', '$2y$10$N/I7M5B5lwM.V2.HWNRUl.5BDzNhIcD1tAehlcGYo5OYkvXO99UNi', 'my_name@gugle.com'),
('karandash', '$2y$10$VdFPCV2zqvjeQtjZg080yuSggAZGzul4W.025Y41oTkFdAsOGRv4K', 'pochta@jandex.ru');

/*
PASSWORDS:
  romashka12 - 7jgkl689
  listik     - hrkith9
  karandash  - ui5y9800lp
*/
