<?php

declare(strict_types=1);

namespace App\Domain\Post;

use Exception;

class PostException extends Exception
{
    public const INVALID_ID                  = 'Incorrect "id" parameter, it required and type string';
    public const INVALID_TITLE               = 'Incorrect "title" parameter, it required and type string';
    public const INVALID_TITLE_LENGTH        = 'Incorrect "title", should be min-max length: ';
    public const INVALID_SLUG                = 'Incorrect "slug" parameter, it required and type string';
    public const INVALID_SLUG_LENGTH         = 'Incorrect "slug", should be min-max length: ';
    public const INVALID_CONTENT             = 'Incorrect "content" parameter, it required and type string';
    public const INVALID_CONTENT_LENGTH      = 'Incorrect "content", should be min-max length: ';
    public const INVALID_HTML_CONTENT        = 'Incorrect "html_content" parameter, it required and type string';
    public const INVALID_HTML_CONTENT_LENGTH = 'Incorrect "html_content", should be min-max length: ';
    public const INVALID_STATUS_ID           = 'Incorrect "status_id" parameter, it required and type int';
    public const INVALID_COMMENTS_COUNT      = 'Incorrect "comments_count" parameter, it required and type int';
    public const INVALID_PUBLISHED           = 'Incorrect "published" parameter, it required and type int';
    public const INVALID_TAGS                = 'Incorrect "tags" parameter, it required and type array';
    public const INVALID_TAG                 = 'Incorrect "tag" parameter, it required and type string';
    public const INVALID_IS_LIKED_DATA       = 'Incorrect "is_liked" data, expected arrays';
    public const INVALID_CREATED_AT          = 'Incorrect "created_at" parameter, it required and type string date';
    public const INVALID_UPDATED_AT          = 'Incorrect "updated_at" parameter, expected string date or empty';

    // like errors
    public const ERROR_NO_AUTH      = 'Изменять рейтинг постов могут только зарегистрированные и авторизованные пользователи.<br /><br /><a href="/registration/main">Зарегистрироваться</a> / <a href="/login">Войти</a>';
    public const ERROR_OWNER        = 'Нельзя изменять рейтинг своего поста';
    public const ERROR_DONT_LIKE    = 'Пока вам недоступен функционал изменения рейтинга постов';
    public const ERROR_ALREADY_LIKE = 'Вы уже изменяли рейтинг этого поста';

    public const NO_CREATE_ENERGY   = 'No energy to create post. Need %d, have %d';
}
