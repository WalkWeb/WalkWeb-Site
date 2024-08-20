<?php

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Пользователи с наибольшей кармой';

if (empty($accounts) || !($accounts instanceof AccountCollection)) {
    throw new AppException('view.rating.account_level: miss accounts');
}

?>

<p class="text center">
    <a href="/top/account/level" title="" class="osnova">Уровень</a> |
    Карма |
    <a href="#" title="" class="osnova">Профессии</a> |
    <a href="#" title="" class="osnova">Сообщества</a> |
    <a href="#" title="" class="osnova">Языки программирования</a>
</p>

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
                <td>' . $account->getPostCount() . '</td>
                <td>' . $account->getCommentCount() . '</td>
               <td><span class="' . $account->getCarmaColoClass() . '">' . $account->getCarmaSign() . $account->getCarma() . '</span></td>
             </tr>';
        $i++;
    }

    echo '</table>';

} else {
    echo '<p>Пользователей нет.</p>';
}
