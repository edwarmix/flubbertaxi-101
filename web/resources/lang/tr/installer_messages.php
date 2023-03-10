<?php 
return [
  'title' => 'Laravel Yükleyici',
  'next' => 'Sonraki adım',
  'back' => 'Öncesi',
  'finish' => 'Düzenlemek',
  'forms' => [
    'errorTitle' => 'Şu hatalar oluştu:',
  ],
  'welcome' => [
    'templateTitle' => 'Hoş geldin',
    'title' => 'Laravel Yükleyici',
    'message' => 'Kolay Kurulum ve Kurulum Sihirbazı.',
    'next' => 'Gereksinimleri Kontrol Edin',
  ],
  'requirements' => [
    'templateTitle' => 'Adım 1 | Sunucu Gereksinimleri',
    'title' => 'Sunucu Gereksinimleri',
    'next' => 'İzinleri Kontrol Et',
  ],
  'permissions' => [
    'templateTitle' => '2. Adım | izinler',
    'title' => 'izinler',
    'next' => 'Ortamı Yapılandır',
  ],
  'environment' => [
    'menu' => [
      'templateTitle' => 'Adım 3 | Ortam Ayarları',
      'title' => 'Ortam Ayarları',
      'desc' => 'Lütfen apps <code>.env</code> dosyasını nasıl yapılandırmak istediğinizi seçin.',
      'wizard-button' => 'Form Sihirbazı Kurulumu',
      'classic-button' => 'Klasik Metin Editörü',
    ],
    'wizard' => [
      'templateTitle' => 'Adım 3 | Ortam Ayarları | Kılavuzlu Sihirbaz',
      'title' => 'Kılavuzlu <code>.env</code> Sihirbazı',
      'tabs' => [
        'environment' => 'Çevre',
        'database' => 'Veri tabanı',
        'application' => 'Başvuru',
      ],
      'form' => [
        'name_required' => 'Bir ortam adı gerekli.',
        'app_name_label' => 'Uygulama ismi',
        'app_name_placeholder' => 'Uygulama ismi',
        'app_environment_label' => 'Uygulama Ortamı',
        'app_environment_label_local' => 'Yerel',
        'app_environment_label_developement' => 'Gelişim',
        'app_environment_label_qa' => 'Qa',
        'app_environment_label_production' => 'Üretme',
        'app_environment_label_other' => 'Başka',
        'app_environment_placeholder_other' => 'Bulunduğunuz ortama girin...',
        'app_debug_label' => 'Uygulama Hata Ayıklama',
        'app_debug_label_true' => 'Doğru',
        'app_debug_label_false' => 'Yanlış',
        'app_log_level_label' => 'Uygulama Günlüğü Düzeyi',
        'app_log_level_label_debug' => 'hata ayıklama',
        'app_log_level_label_info' => 'bilgi',
        'app_log_level_label_notice' => 'fark etme',
        'app_log_level_label_warning' => 'uyarı',
        'app_log_level_label_error' => 'hata',
        'app_log_level_label_critical' => 'kritik',
        'app_log_level_label_alert' => 'Alarm',
        'app_log_level_label_emergency' => 'acil Durum',
        'app_url_label' => 'Uygulama URL&#39;si',
        'app_url_placeholder' => 'Uygulama URL&#39;si',
        'db_connection_failed' => 'Veritabanına bağlanılamadı.',
        'db_connection_label' => 'Veritabanı Bağlantısı',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_sqlite' => 'sqlit',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Veritabanı Ana Bilgisayarı',
        'db_host_placeholder' => 'Veritabanı Ana Bilgisayarı',
        'db_port_label' => 'Veritabanı Bağlantı Noktası',
        'db_port_placeholder' => 'Veritabanı Bağlantı Noktası',
        'db_name_label' => 'Veri tabanı ismi',
        'db_name_placeholder' => 'Veri tabanı ismi',
        'db_username_label' => 'Veritabanı Kullanıcı Adı',
        'db_username_placeholder' => 'Veritabanı Kullanıcı Adı',
        'db_password_label' => 'Veritabanı Parolası',
        'db_password_placeholder' => 'Veritabanı Parolası',
        'app_tabs' => [
          'more_info' => 'Daha fazla bilgi',
          'broadcasting_title' => 'Yayın, Önbelleğe Alma, Oturum ve Kuyruk',
          'broadcasting_label' => 'Yayın Sürücüsü',
          'broadcasting_placeholder' => 'Yayın Sürücüsü',
          'cache_label' => 'Önbellek Sürücüsü',
          'cache_placeholder' => 'Önbellek Sürücüsü',
          'session_label' => 'Oturum Sürücüsü',
          'session_placeholder' => 'Oturum Sürücüsü',
          'queue_label' => 'Kuyruk Sürücüsü',
          'queue_placeholder' => 'Kuyruk Sürücüsü',
          'redis_label' => 'Redis Sürücüsü',
          'redis_host' => 'Redis Ana Bilgisayarı',
          'redis_password' => 'Redis Şifresi',
          'redis_port' => 'Redis Limanı',
          'mail_label' => 'Posta',
          'mail_driver_label' => 'Posta Sürücüsü',
          'mail_driver_placeholder' => 'Posta Sürücüsü',
          'mail_host_label' => 'Posta Ana Bilgisayarı',
          'mail_host_placeholder' => 'Posta Ana Bilgisayarı',
          'mail_port_label' => 'Posta Bağlantı Noktası',
          'mail_port_placeholder' => 'Posta Bağlantı Noktası',
          'mail_username_label' => 'Posta Kullanıcı Adı',
          'mail_username_placeholder' => 'Posta Kullanıcı Adı',
          'mail_password_label' => 'Posta Şifresi',
          'mail_password_placeholder' => 'Posta Şifresi',
          'mail_encryption_label' => 'Posta Şifreleme',
          'mail_encryption_placeholder' => 'Posta Şifreleme',
          'pusher_label' => 'itici',
          'pusher_app_id_label' => 'İtici Uygulama Kimliği',
          'pusher_app_id_palceholder' => 'İtici Uygulama Kimliği',
          'pusher_app_key_label' => 'İtici Uygulama Anahtarı',
          'pusher_app_key_palceholder' => 'İtici Uygulama Anahtarı',
          'pusher_app_secret_label' => 'İtici Uygulama Sırrı',
          'pusher_app_secret_palceholder' => 'İtici Uygulama Sırrı',
        ],
        'buttons' => [
          'setup_database' => 'Veritabanı Kurulumu',
          'setup_application' => 'Kurulum Uygulaması',
          'install' => 'Düzenlemek',
        ],
      ],
    ],
    'classic' => [
      'templateTitle' => 'Adım 3 | Ortam Ayarları | Klasik Editör',
      'title' => 'Klasik Ortam Düzenleyicisi',
      'save' => '.env&#39;yi kaydet',
      'back' => 'Form Sihirbazını Kullan',
      'install' => 'Kaydet ve Yükle',
    ],
    'success' => '.env dosya ayarlarınız kaydedildi.',
    'errors' => '.env dosyası kaydedilemiyor, Lütfen manuel olarak oluşturun.',
    'title' => '',
    'save' => '',
  ],
  'install' => 'Düzenlemek',
  'installed' => [
    'success_log_message' => 'Laravel Installer başarıyla KURULDU',
  ],
  'final' => [
    'title' => 'Kurulum Bitti',
    'templateTitle' => 'Kurulum Bitti',
    'finished' => 'Uygulama başarıyla yüklendi.',
    'migration' => 'Taşıma ve Tohum Konsolu Çıktısı:',
    'console' => 'Uygulama Konsolu Çıktısı:',
    'log' => 'Kurulum Günlüğü Girişi:',
    'env' => 'Nihai .env Dosyası:',
    'exit' => 'Çıkmak için burayı tıklayın',
  ],
  'updater' => [
    'title' => 'Laravel Güncelleyici',
    'welcome' => [
      'title' => 'Güncelleyiciye Hoş Geldiniz',
      'message' => 'Güncelleme sihirbazına hoş geldiniz.',
    ],
    'overview' => [
      'title' => 'genel bakış',
      'message' => '1 güncelleme var.| :number güncelleme var.',
      'install_updates' => 'Güncellemeleri yükle',
    ],
    'final' => [
      'title' => 'bitmiş',
      'finished' => 'Uygulamanın veritabanı başarıyla güncellendi.',
      'exit' => 'Çıkmak için burayı tıklayın',
    ],
    'log' => [
      'success_message' => 'Laravel Installer başarıyla GÜNCELLENDİ',
    ],
  ],
];