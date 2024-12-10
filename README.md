# 非フレームワークのPHPで作るMVCモデルのサンプル

## 概要

Laravel等フレームワークを使わずにPHPでMVCモデルを的なものを作るサンプルです。  
リポジトリ名はバニラを名乗っていますが、一部ライブラリを使っているので完全なバニラではありません。

## デモ環境

[https://member-management.hazama-shoin.com/](https://member-management.hazama-shoin.com/){:target="_blank"}

## 利用方法

### セットアップ手順

1. [Docker Desktop](https://docs.docker.com/desktop/) をインストール

2. このリポジトリをクローン（またはダウンロード）

    ``` bash
    git clone https://github.com/hazama-shoin/vanilla-php-sample.git
    ```

3. `vanilla-php-sample` ディレクトリに移動

    ``` bash
    cd vanilla-php-sample
    ```

4. セットアップスクリプトを実行

    ``` bash
    sh setup.sh
    ```

### 2回目以降の起動手順

* Docker Desktopから `vanilla-php-sample` を起動
* もしくは、 `vanilla-php-sample` ディレクトリ以下で以下コマンドを実行

    ``` bash
    docker compose up -d
    ```

### 停止手順

* Docker Desktopから `vanilla-php-sample` を停止
* もしくは、 `vanilla-php-sample` ディレクトリ以下で以下コマンドを実行

    ``` bash
    docker compose stop
    ```

### アンインストール手順

* Docker Desktopから `vanilla-php-sample` を削除
* もしくは、 `vanilla-php-sample` ディレクトリ以下で以下コマンドを実行

    ``` bash
    docker compose down
    ```

> [!IMPORTANT]
> 上記操作ではボリュームやイメージは削除されません。  
> ボリューム等も含めて全て削除したい場合は、自己責任で以下コマンドを実行してください。  
>
> ``` bash
> docker compose down --rmi all --volumes --remove-orphans
> ```

## ライセンスと制限事項

当リポジトリは [MITライセンス](/LICENSE) に基づいて公開されています。  
商用・非商用問わず、用途に制限はありません。

### 利用ライブラリ

* [phpdotenv](https://github.com/vlucas/phpdotenv)

    > BSD 3-Clause License  
    > Copyright (c) 2014, Graham Campbell.  
    > Copyright (c) 2013, Vance Lucas.  
    > <https://github.com/vlucas/phpdotenv/blob/master/LICENSE>

* [PHPMailer](https://github.com/PHPMailer/PHPMailer)

    > GNU LESSER GENERAL PUBLIC LICENSE Version 2.1, February 1999  
    > Copyright (C) 1991, 1999 Free Software Foundation, Inc.  
    > 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA  
    > <https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE>

* [smarty](https://github.com/smarty-php/smarty)

    > GNU LESSER GENERAL PUBLIC LICENSE Version 3, 29 June 2007  
    > Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>  
    > <https://github.com/smarty-php/smarty/blob/master/LICENSE>

### 利用フォント

* [Noto Sans Japanese](https://fonts.google.com/noto/specimen/Noto+Sans+JP)

    > Copyright 2014-2021 Adobe (<http://www.adobe.com/>)  
    > SIL OPEN FONT LICENSE Version 1.1 - 26 February 2007  
    > <https://fonts.google.com/noto/specimen/Noto+Sans+JP/license>

* [font-awesome](https://github.com/FortAwesome/Font-Awesome/tree/6.7.1)

    > Fonticons, Inc. (<https://fontawesome.com>)  
    > Icons: CC BY 4.0 License (<https://creativecommons.org/licenses/by/4.0/>)  
    >
    > Fonts: SIL OFL 1.1 License  
    > Copyright (c) 2024 Fonticons, Inc. (<https://fontawesome.com>)  
    > SIL OPEN FONT LICENSE Version 1.1 - 26 February 2007
    >
    > Code: MIT License (<https://opensource.org/licenses/MIT>) Copyright 2024 Fonticons, Inc.  
    > <https://github.com/FortAwesome/Font-Awesome/blob/6.x/LICENSE.txt>
