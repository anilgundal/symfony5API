
# Symfony 5 Restful API

Symfony framework'ünü ilk kez kullandığım bir çalışma.

Bu çaılışma içerisinde Docker kurulum dosyalarını, Postman colletion dosyasını ve çalışma dosyalarını bulacaksınız.

```bash
git clone https://github.com/anilgundal/symfony5API.git
```

komutunu kullanarak Repoyu bilgisayarınıza çekin.

## Docker Kurulumu  

```bash  
  cd ./docker
  cp .env.example .env
  cp docker-compose.yml.example docker-compose.yml
  docker compose up -d
```

komutlarını kullanarak servisleri çalıştırın.

## Symfony Projesini çalışırma

```bash
cd src\
cp .env.example .env
composer install
```

## Postman Colletion'ı eklemek

Postman uygulamasını açtığınızda çalışma ortamınızı seçin.
import butonunu kullanarak repo içerisinde yer alan Symfony5-API.postman_collection.json
dosyasını seçin. Artık istek göndermeye hazırsınız! 