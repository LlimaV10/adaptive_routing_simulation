Чтобы запустить програмный продукт нужно установить Apache в любом виде и перебросить все файлы репозитория в корень вашей папки сервера. После чего зайти на страницу localhost.

Параметры нужной вам сети можно корректировать в файле index_new.php в корне репозитория:
![index_new](https://raw.githubusercontent.com/LlimaV10/adaptive_routing_simulation/master/README_img/index_new.PNG)

В 6 строке замените массив весов на нужный вам
	$weights = new Weights(array(2, 4, 5, 9, 10, 12, 18, 21, 23, 26, 28, 32));
В 8 строке первый параметр указывает на среднее количество каналов подсоедененных к узлу сети. Третий - это число N, которое значит что каждый N-ный узел в регионе будет напрямую подсоединен к рабочей станции которая может отсылать||принимать сообщения.
	$net = new Network(3.5, $weights, 2);
После требуется добавить нужное количество регионов строками:
	$net->add_region(new Region(8));
Где число передаваемое в конструктор класса Region - количество узлов в нем.

Более ничего изменять не требуется.

Главное меню:
![main_menu](https://raw.githubusercontent.com/LlimaV10/adaptive_routing_simulation/master/README_img/main_menu.PNG)
Если программа запущена впервые нажмите "Створити нову мережу". Генерация может занять небольшое время.
Если же сеть уже была ранее сгенерирована - можно загрузить её из сессии вашего браузера.

![Network](https://raw.githubusercontent.com/LlimaV10/adaptive_routing_simulation/master/README_img/Network.PNG)
После перед вами будет сеть внутри которой и будет производится симуляция.

![routing table](https://raw.githubusercontent.com/LlimaV10/adaptive_routing_simulation/master/README_img/routing%20table.PNG)
А также таблица маршрутизации для станции 24.
(Станция для вывода таблицы задается в файле main.php[14 строка], также сверху есть возможность поменять ширину и высоту в зависимости от разрешения вашего экрана)

![parameters](https://raw.githubusercontent.com/LlimaV10/adaptive_routing_simulation/master/README_img/parameters.PNG)
В левой части экрана выберите режим маршрутизации, параметры симуляции и тип канала. После чего нажмите кнопку "Надіслати".

![simulation](https://raw.githubusercontent.com/LlimaV10/adaptive_routing_simulation/master/README_img/simulation.png)
Симуляция запущена и можно наблюдать поведение сети.
В левом меню можно регулировать скорость симуляции.

![result_table](https://raw.githubusercontent.com/LlimaV10/adaptive_routing_simulation/master/README_img/result_table.PNG)
После окончания симуляции будет выведена таблица с результатами.