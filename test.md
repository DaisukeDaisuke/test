ビルド方法について簡潔に教えますね  
  
https://github.com/pmmp/php-build-scripts  
php-build-scriptsの README.md を元に説明します  
  
ビルドに使う推奨gccは  
https://github.com/pmmp/musl-cross-make  
とされていますが、それはAndroid向けに色々改造されているで、  
  
https://github.com/richfelker/musl-cross-make  
を推奨します  
  
musl-cross-makeをダウンロードして、展開  
  
`config.mak.dist`があるフォルダに  
`config.mak`  
を作成して、その中に  
```
TARGET = aarch64-linux-musl
OUTPUT = (ビルド結果を収納するフォルダ)
```
と書きましょう。  
  
> ビルド結果を収納するフォルダを`/usr/local`など以外に設定した場合は、php-build-scriptビルド時に  
> export PATH="$PATH:(ビルド結果を収納するフォルダ)/bin"  
> でパスを通しましょう。  
  
そして、  
`make -j4`  
(数分～数時間待機)  
and  
`make install`  
  
#### php-build-script
https://github.com/pmmp/php-build-scripts  
をダウンロード、展開しましょう  

以下のどちらかのコマンドを打ち、ビルドをしましょう  
GD無し    
`./compile.sh -t android-aarch64 -x -s -j4 -f`
    
GDあり  
`./compile.sh -t android-aarch64 -x -s -j4 -f -g`
  
ビルド結果は./bin/php7/bin/php(かな...?)にあります。
