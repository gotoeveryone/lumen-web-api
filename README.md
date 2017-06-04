# Web API #

[Lumen](https://lumen.laravel.com/)を利用したWebAPIアプリです。

## 前提

以下がインストールされていること。

- PHP7.x
- Composer

## セットアップ

1. `.env.example`をもとに、`.env`ファイルを生成します。
2. プロジェクトのルートディレクトリで以下を実行します。

```
$ composer install
```

## ログ出力

変数`LOG_DIR`に`web-api.log`としてログ出力します。

## DB接続
  
初期では`MySQL`を利用しますが、必要に応じて変更してください。

また、ログインのAPI認証にREDISが使われています。  
`REDIS_HOST`が環境変数から取得できない場合、上記で設定したデータベースの`users`テーブルを利用して認証を行います。
