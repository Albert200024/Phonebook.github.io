<?php

// Путь к файлу JSON для хранения контактов
$contactsFile = 'contacts.json';

// Функция для получения контактов из файла JSON
function getContacts() {
    global $contactsFile;
    // Если файл существует, считываем его содержимость
    if (file_exists($contactsFile)) {
        $json = file_get_contents($contactsFile);
        return json_decode($json, true);
    } else {
        return [];
    }
}

// Функция для сохранения контактов в файл JSON
function saveContacts($contacts) {
    global $contactsFile;
    // Сохраняем контакты в JSON формате
    $json = json_encode($contacts);
    file_put_contents($contactsFile, $json);
}

// Добавление нового контакта
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['phone'])) {
    $contacts = getContacts();
    $newContact = [
        'name' => $_POST['name'],
        'phone' => $_POST['phone']
    ];
    // Добавляем новый контакт в массив
    $contacts[] = $newContact;
    // Сохраняем обновленные контакты в файл
    saveContacts($contacts);

}

// Удаление контакта
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $contacts = getContacts();
    $id = $_GET['id'];
    // Удаляем контакт с указанным id
    unset($contacts[$id]);
    // Переиндексируем массив
    $contacts = array_values($contacts);
    // Сохраняем обновленные контакты в файл
    saveContacts($contacts);
    header("Location: index.php");
}

// Получение всех контактов
$contacts = getContacts();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Телефонный справочник</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1 class="title">Телефонный справочник</h1>

<form method="post">
    <input type="text" name="name" placeholder="Имя" required><br>
    <input type="tel" name="phone" placeholder="Телефон" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required><br>
    <input type="submit" value="Добавить">
</form>

<table>
    <tr>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Удалить</th>
    </tr>
    <?php foreach ($contacts as $key => $contact): ?>
        <tr>
            <td><?= $contact['name'] ?></td>
            <td><?= $contact['phone'] ?></td>
            <td><a href="?delete&id=<?= $key ?>" onclick="return confirm('Вы уверены?')">Удалить</a></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
