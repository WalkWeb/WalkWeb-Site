<?php

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Самые высокоуровневые аккаунты';

if (empty($accounts) || !($accounts instanceof AccountCollection)) {
    throw new AppException('view.rating.account_level: miss accounts');
}

?>

<p class="text" align="center">
    Уровень аккаунта |
    <a href="#" title="" class="osnova">Карма</a> |
    <a href="#" title="" class="osnova">Профессии</a>
</p>

<!-- TODO Доработать визуально: добавить иконки топ-3, добавить им цветовое выделение имен + размер, добавить другие стили для имен аккаунтов (не простые ссылки) и прочие мелочи -->

<?php

if (count($accounts) > 0) {
    echo '<table>
          <tr><th>№</th><th>Аватар</th><th>Уровень</th><th>Опыт</th><th>Имя</th><th>Постов</th><th>Комментариев</th><th>Карма</th></tr>';
    $i = 1;
    foreach ($accounts as $account) {
        echo '<tr>
                <td>' . $i . '</td>
                <td class="avatar" style="background-image: url(' . $account->getAvatar() . ')"></td>
                <td>' . $account->getLevel() . '</td>
                <td>' . $account->getExp() . '</td>
                <td><a href="/u/' . $account->getName() . '" >' . $account->getName() . '</a></td>
                <td>#</td>
                <td>#</td>
                <td>' . $account->getCarma() . '</td>
             </tr>';
        $i++;
    }

    echo '</table>';

} else {
    echo '<p>Пользователей нет.</p>';
}
