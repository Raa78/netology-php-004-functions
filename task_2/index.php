<?php
declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

$items = [];


function checkAndPrintOperations(array $operations, array $items)
{

    if (!count($items)) {
        $menu = $operations;
        unset($menu[OPERATION_DELETE]);

        return $menu;
    }

    return $operations;
}


function printShoppingList(array $items): void
{
    if (count($items)) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode("\n", $items) . "\n";
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
}


function inputSectionMenu(array $operations, array $items): string
{
    echo 'Выберите операцию для выполнения: ' . PHP_EOL;
    // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
    $menu = checkAndPrintOperations($operations, $items);

    echo implode(PHP_EOL, $menu) . PHP_EOL . '> ';
    $operationNumber = trim(fgets(STDIN));

    return $operationNumber;
}


function checkMenuItem(string $operationNumber, array $operations): bool
{
     if (!array_key_exists($operationNumber, $operations)) {
            system('clear');

            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;

            return false;
        }

        return true;
}


function printStartMenu(array $items, array $operations)
{
    // выводим спискок покупок
    printShoppingList($items);

    // запрашиваем ввести номер операции (выбрать позицию из меню)
    $operationNumber = inputSectionMenu($operations, $items);

    // проверка - есть ли выбраное меню в списке
    $checkItem = checkMenuItem($operationNumber, $operations);

    if (!$checkItem) {
        printStartMenu($items, $operations);
    }

    return $operationNumber;
}


do {

    system('clear');
//    system('cls'); // windows

    $operationNumber = printStartMenu($items, $operations);


    echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            echo "Введение название товара для добавления в список: \n> ";
            $itemName = trim(fgets(STDIN));
            $items[] = $itemName;
            break;

        case OPERATION_DELETE:
            // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
            echo 'Текущий список покупок:' . PHP_EOL;
            echo 'Список покупок: ' . PHP_EOL;
            echo implode("\n", $items) . "\n";

            echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
            $itemName = trim(fgets(STDIN));

            if (in_array($itemName, $items, true) !== false) {
                while (($key = array_search($itemName, $items, true)) !== false) {
                    unset($items[$key]);
                }
            }
            break;

        case OPERATION_PRINT:
            echo 'Ваш список покупок: ' . PHP_EOL;
            echo implode(PHP_EOL, $items) . PHP_EOL;
            echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
            echo 'Нажмите enter для продолжения';
            fgets(STDIN);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;
