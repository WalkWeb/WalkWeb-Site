<?php

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Пользователи портала';

if (empty($accounts) || !($accounts instanceof AccountCollection)) {
    throw new AppException('view.account.list: miss accounts');
}

if (empty($total) || !is_int($total)) {
    throw new AppException('view.account.list: miss total');
}

$pagination = $pagination ?? '';

?>

<h1>Пользователи портала</h1>

<?php

if ($total === 0) {
    echo '<p>Пользователей нет.</p>';
} else {
    echo '<table>
          <tr>
              <th>Аватар</th>
              <th>Имя</th>
              <th>Уровень</th>
              <th>Группа</th>
              <th>Статус</th>
              <th>Постов</th>
              <th>Комментариев</th>
              <th>Карма</th>
          </tr>';

    foreach ($accounts as $account) {
        echo '<tr>
                <td class="avatar" style="background-image: url(' . $account->getAvatar() . ')"></td>
                <td><a href="/u/' . $account->getName() . '" >' . $account->getName() . '</a></td>
                <td>' . $account->getLevel() . '</td>
                <td>' . $account->getGroup()->getName() . '</td>
                <td>' . $account->getStatus()->getName() . '</td>
                <td>' . $account->getPostCount() . '</td>
                <td>' . $account->getCommentCount() . '</td>
                <td><span class="' . $account->getCarmaColoClass() . '">' . $account->getCarmaSign() . $account->getCarma() . '</span></td>
             </tr>';
    }

    echo '</table>';
}

echo "<div class='pagination'>$pagination</div>";
