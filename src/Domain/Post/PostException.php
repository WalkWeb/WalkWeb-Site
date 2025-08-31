<?php

declare(strict_types=1);

namespace App\Domain\Post;

use Exception;

class PostException extends Exception
{
    public const string INVALID_ID                  = 'Incorrect "id" parameter, it required and type string (uuid)';
    public const string INVALID_TITLE               = 'Incorrect "title" parameter, it required and type string';
    public const string INVALID_TITLE_LENGTH        = 'Incorrect "title", should be min-max length: ';
    public const string INVALID_SLUG                = 'Incorrect "slug" parameter, it required and type string';
    public const string INVALID_SLUG_LENGTH         = 'Incorrect "slug", should be min-max length: ';
    public const string INVALID_CONTENT             = 'Incorrect "content" parameter, it required and type string';
    public const string INVALID_CONTENT_LENGTH      = 'Incorrect "content", should be min-max length: ';
    public const string INVALID_HTML_CONTENT        = 'Incorrect "html_content" parameter, it required and type string';
    public const string INVALID_HTML_CONTENT_LENGTH = 'Incorrect "html_content", should be min-max length: ';
    public const string INVALID_STATUS_ID           = 'Incorrect "status_id" parameter, it required and type int';
    public const string INVALID_COMMENTS_COUNT      = 'Incorrect "comments_count" parameter, it required and type int';
    public const string INVALID_PUBLISHED           = 'Incorrect "published" parameter, it required and type int';
    public const string INVALID_TAGS                = 'Incorrect "tags" parameter, it required and type array';
    public const string INVALID_TAG                 = 'Incorrect "tag" parameter, it required and type string';
    public const string INVALID_IS_LIKED            = 'Incorrect "is_liked" data, expected bool';
    public const string INVALID_COMMUNITY_SLUG      = 'Incorrect "community_slug" parameter, it required and type string or null';
    public const string INVALID_COMMUNITY_NAME      = 'Incorrect "community_name" parameter, it required and type string or null';
    public const string INVALID_CREATED_AT          = 'Incorrect "created_at" parameter, it required and type string date';
    public const string INVALID_UPDATED_AT          = 'Incorrect "updated_at" parameter, expected string date or empty';

    // for PostList
    public const string INVALID_AUTHOR_NAME         = 'Incorrect "author_name" parameter, it required and type string';
    public const string INVALID_TINY_TAGS           = 'Incorrect "tags" parameter, it required and type array';
    public const string INVALID_TAG_DATA            = 'Incorrect "tags" data, expected array[array]';
    public const string INVALID_TAG_SLUG            = 'Incorrect "tag[slug]" parameter, it required and type string';
    public const string INVALID_TAG_NAME            = 'Incorrect "tag[name]" parameter, it required and type string';

    // like errors
    public const string ERROR_NO_AUTH      = 'Изменять рейтинг постов могут только зарегистрированные и авторизованные пользователи.<br /><br /><a href="/registration/main">Зарегистрироваться</a> / <a href="/login">Войти</a>';
    public const string ERROR_OWNER        = 'Нельзя изменять рейтинг своего поста';
    public const string ERROR_DONT_LIKE    = 'Пока вам недоступен функционал изменения рейтинга постов';
    public const string ERROR_ALREADY_LIKE = 'Вы уже изменяли рейтинг этого поста';

    public const string NO_CREATE_ENERGY   = 'No energy to create post. Need %d, have %d';
    public const string ALREADY_EXIST      = 'PostCollection: post to be added already exists';
    public const string EXPECTED_ARRAY     = 'PostCollectionFactory: expected array data';

    // repository
    public const string GET_AUTHOR_ERROR   = 'Author post could not be reached';
    public const string INVALID_COMMUNITY  = 'Community given, but not found, slug: ';
}
