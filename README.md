# 作業開始時
### エイリアス設定
```Bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```
### Sail起動
```Bash
sail up -d &&
sail npm run dev
```

# 初期設定
### Composer依存関係をインストール
```Bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```
### エイリアス設定
```Bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```
### .env生成
```Bash
cp .env.example
```
### キー生成
```Bash
sail artisan key:generate
```
### Sail起動
```Bash
sail up -d
```
### Breezeインストール
※今回はPHPUnit, Vue with Inertiaを選択
```Bash
sail composer require laravel/breeze --dev && sail artisan breeze:install
```
### SCSS依存ライブラリのインストール
```Bash
sail npm install -D sass-embedded
```
### テーブル構築
```Bash
sail artisan migrate
```

# URL
+ 開発環境: http://localhost
+ DB: http://localhost:3306
+ phpMyAdmin: http://localhost:8888
+ Redis: http://localhost:6379
