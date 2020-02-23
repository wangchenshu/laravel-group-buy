## 如何開始

### 修改相關設定
> 將 .env.example 更名為 .env
>
> 修改資料庫連線設定
>
> 修改 LINEBOT_TOKEN, LINEBOT_SECRET 以及 LINE_USER_ID
>
> 如需 支持 JWT，請記得自行產生 JWT_SECRET，並加到 .env 檔
>

### 匯入資料庫
> 將 group_buy_2020-02-23.sql.gz 解壓並匯入本機資料庫
>

### 支持 Docker 運行
> $ docker-compose up -d
>

### 本機直接執行
> sh startup.sh
>

### 一支簡單的 Line 購物 Bot 就完成了，Enjoy.
