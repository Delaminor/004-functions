<?php

declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

/**
 * Выводим список покупок
 *
 * @param array<int, string> $items
 */
function printItems(array $items): void
{
    if (count($items)) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode(PHP_EOL, $items) . PHP_EOL;
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
}

/**
 * Формируем список доступных операций
 *
 * @param array<int, string> $items
 * @return array<int, string>
 */
function getAvailableOperations(array $items): array
{
    $operations = [
        OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
        OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
        OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    ];

    if (count($items)) {
        $operations[OPERATION_DELETE] = OPERATION_DELETE . '. Удалить товар из списка покупок.';
        ksort($operations);
    }

    return $operations;
}

/**
 * Выводим список покупок, доступные операции и запрашиваемм следующее действие..
 *
 * @param array<int, string> $items
 * @return int
 */
function requestOperation(array $items): int
{
    do {
        system('clear');
        // system('cls'); // windows

        $operations = getAvailableOperations($items);

        printItems($items);

        echo 'Выберите операцию для выполнения: ' . PHP_EOL;
        echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
        $operationNumber = (int) trim(fgets(STDIN));

        if (!array_key_exists($operationNumber, $operations)) {
            system('clear');
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }
    } while (!array_key_exists($operationNumber, $operations));

    return $operationNumber;
}

/**
 * Добавляем товар в список покупок
 *
 * @param array<int, string> $items
 * @return array<int, string>
 */
function addItem(array $items): array
{
    echo "Введение название товара для добавления в список: \n> ";
    $itemName = trim(fgets(STDIN));

    $items[] = $itemName;

    return $items;
}

/**
 * Удаляем товар из списка покупок
 *
 * @param array<int, string> $items
 * @return array<int, string>
 */
function deleteItem(array $items): array
{
    if (!count($items)) {
        echo 'Список покупок пуст.' . PHP_EOL;
        return $items;
    }

    echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    if (in_array($itemName, $items, true) !== false) {
        while (($key = array_search($itemName, $items, true)) !== false) {
            unset($items[$key]);
        }

        $items = array_values($items);
    }

    return $items;
}

/**
 * Отображаем список покупок
 *
 * @param array<int, string> $items
 */
function showItems(array $items): void
{
    printItems($items);
    echo 'Всего ' . count($items) . ' позиций. ' . PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

$items = [];

$operationNames = [
    OPERATION_EXIT => 'Завершить программу.',
    OPERATION_ADD => 'Добавить товар в список покупок.',
    OPERATION_DELETE => 'Удалить товар из списка покупок.',
    OPERATION_PRINT => 'Отобразить список покупок.',
];

do {
    $operationNumber = requestOperation($items);

    echo 'Выбрана операция: ' . $operationNames[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            $items = addItem($items);
            break;

        case OPERATION_DELETE:
            $items = deleteItem($items);
            break;

        case OPERATION_PRINT:
            showItems($items);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;
