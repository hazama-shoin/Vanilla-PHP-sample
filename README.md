# 非フレームワークのPHPで作るMVCモデルのサンプル

## 概要

Laravel等を使わずにPHPでMVCモデルを的なことをやるサンプルです。  
リポジトリ名はバニラを名乗っていますが、一部ライブラリを使っているので完全なバニラではありません。

デモ環境: <https://member-management.hazama-shoin.com/>

モック環境: <https://hazama-shoin.github.io/Vanilla-PHP-sample/>  
※こちらはモック環境なので、実際のデータ登録・削除などの実行はできません。

## セットアップ手順

1. [Docker Desktop](https://docs.docker.com/desktop/) をインストール

2. このリポジトリをクローン（またはダウンロード）

    ``` bash
    git clone https://github.com/hazama-shoin/Vanilla-PHP-sample.git
    ```

3. `Vanilla-PHP-sample` ディレクトリに移動

    ``` bash
    cd Vanilla-PHP-sample
    ```

4. セットアップスクリプトを実行

    ``` bash
    sh setup.sh
    ```

## 2回目以降の起動手順

* Docker Desktopから `vanilla-php-sample` を起動
* もしくは、 `Vanilla-PHP-sample` ディレクトリ以下で以下コマンドを実行

    ``` bash
    docker compose up -d
    ```

## 停止手順

* Docker Desktopから `vanilla-php-sample` を停止
* もしくは、 `Vanilla-PHP-sample` ディレクトリ以下で以下コマンドを実行

    ``` bash
    docker compose stop
    ```

## アンインストール手順

* Docker Desktopから `vanilla-php-sample` を削除
* もしくは、 `Vanilla-PHP-sample` ディレクトリ以下で以下コマンドを実行

    ``` bash
    docker compose down
    ```
