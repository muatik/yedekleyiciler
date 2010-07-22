YEDEKLEME SİSTEMİ
Yazan: mustafa atik mustafa.atik@botego.com muatik@gmail.com
Tarih: 22 temm 2010

Veritabanı ve proje kod dosyaları otomatik ve düzenli olarak yedeklenmektedir.

Veritabanını yedekleyen yedekleyici.php, dosyaları yedekleyen ise dosyaci.php betikleridir. cron job olarak bu betikler çalıştırılır. 

Her yeni proje için bu iki dosyada değişiklik yapmak gerekir; yeni projenin dizin adını ve veritabanı adını dosyalara girilir.

Eskimiş yedekler ise silinir.

İşlem raporları mustafa.atik@gmail.com adresine gönderilmektedir.

Betiklerin bulunduğu dizin: /root/dbYedek/
Veritabanı yedeklerinin bulunduğu dizin: /root/dbYedek/yedekler
Dosya yedeklerinin bulunduğı dizin: /root/dbYedek/dosya

dosya ve yedekler dizinleri içersinde 1, 3, 10, 30 isimli dört adet dizin daha olmalıdır. 
4:33 pm



Günlük, üç günde bir, on günde bir ve her ay başı yedekleme yapılmaktadır.

yazılmış cron'lar aşağıdadır:

#VERİTABANINI YEDEKLEYENLER
#günlük alınan yedek
0 4 * * * /usr/local/bin/php /root/dbYedek/yedekleyici.php 1
#her ay başı alınan yedek
0 7 1 * * /usr/local/bin/php /root/dbYedek/yedekleyici.php 30
#üç günde bir alınan yedek
0 5 */3 * * /usr/local/bin/php /root/dbYedek/yedekleyici.php 3
#10 günde bir alınan yedek
30 5 */10 * * /usr/local/bin/php /root/dbYedek/yedekleyici.php 10

#DOSYALARI YEDEKLEYENLER
#günlük alınan yedek
0 4 * * * /usr/local/bin/php /root/dbYedek/dosyaci.php 1
#her ay başı alınan yedek
0 7 1 * * /usr/local/bin/php /root/dbYedek/dosyaci.php 30
#üç günde bir alınan yedek
0 5 */3 * * /usr/local/bin/php /root/dbYedek/dosyaci.php 3
#10 günde bir alınan yedek
30 5 */10 * * /usr/local/bin/php /root/dbYedek/dosyaci.php 10
