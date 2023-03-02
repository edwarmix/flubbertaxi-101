<?php 
return [
  'title' => 'Penginstal Laravel',
  'next' => 'Langkah berikutnya',
  'back' => 'Sebelumnya',
  'finish' => 'Install',
  'forms' => [
    'errorTitle' => 'Kesalahan berikut terjadi:',
  ],
  'welcome' => [
    'templateTitle' => 'Selamat datang',
    'title' => 'Penginstal Laravel',
    'message' => 'Wizard Instalasi dan Pengaturan yang Mudah.',
    'next' => 'Periksa Persyaratan',
  ],
  'requirements' => [
    'templateTitle' => 'Langkah 1 | Persyaratan Server',
    'title' => 'Persyaratan Server',
    'next' => 'Periksa Izin',
  ],
  'permissions' => [
    'templateTitle' => 'Langkah 2 | Izin',
    'title' => 'Izin',
    'next' => 'Konfigurasikan Lingkungan',
  ],
  'environment' => [
    'menu' => [
      'templateTitle' => 'Langkah 3 | Pengaturan Lingkungan',
      'title' => 'Pengaturan Lingkungan',
      'desc' => 'Silakan pilih bagaimana Anda ingin mengonfigurasi file <code>.env</code> aplikasi.',
      'wizard-button' => 'Penyiapan Formulir Wizard',
      'classic-button' => 'Editor Teks Klasik',
    ],
    'wizard' => [
      'templateTitle' => 'Langkah 3 | Pengaturan Lingkungan | Penyihir Terpandu',
      'title' => '<code>.env</code> Wizard',
      'tabs' => [
        'environment' => 'Lingkungan',
        'database' => 'Basis Data',
        'application' => 'Aplikasi',
      ],
      'form' => [
        'name_required' => 'Nama lingkungan wajib diisi.',
        'app_name_label' => 'Nama aplikasi',
        'app_name_placeholder' => 'Nama aplikasi',
        'app_environment_label' => 'Lingkungan Aplikasi',
        'app_environment_label_local' => 'Lokal',
        'app_environment_label_developement' => 'Perkembangan',
        'app_environment_label_qa' => 'Q',
        'app_environment_label_production' => 'Produksi',
        'app_environment_label_other' => 'Lainnya',
        'app_environment_placeholder_other' => 'Masuki lingkunganmu...',
        'app_debug_label' => 'Debug Aplikasi',
        'app_debug_label_true' => 'BENAR',
        'app_debug_label_false' => 'PALSU',
        'app_log_level_label' => 'Tingkat Log Aplikasi',
        'app_log_level_label_debug' => 'debug',
        'app_log_level_label_info' => 'informasi',
        'app_log_level_label_notice' => 'melihat',
        'app_log_level_label_warning' => 'peringatan',
        'app_log_level_label_error' => 'kesalahan',
        'app_log_level_label_critical' => 'kritis',
        'app_log_level_label_alert' => 'peringatan',
        'app_log_level_label_emergency' => 'keadaan darurat',
        'app_url_label' => 'Url Aplikasi',
        'app_url_placeholder' => 'Url Aplikasi',
        'db_connection_failed' => 'Tidak dapat terhubung ke database.',
        'db_connection_label' => 'Koneksi Basis Data',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Tuan Rumah Basis Data',
        'db_host_placeholder' => 'Tuan Rumah Basis Data',
        'db_port_label' => 'Pelabuhan Basis Data',
        'db_port_placeholder' => 'Pelabuhan Basis Data',
        'db_name_label' => 'Nama Basis Data',
        'db_name_placeholder' => 'Nama Basis Data',
        'db_username_label' => 'Nama Pengguna Basis Data',
        'db_username_placeholder' => 'Nama Pengguna Basis Data',
        'db_password_label' => 'Kata Sandi Basis Data',
        'db_password_placeholder' => 'Kata Sandi Basis Data',
        'app_tabs' => [
          'more_info' => 'Info lebih lanjut',
          'broadcasting_title' => 'Penyiaran, Caching, Sesi, &amp; Antrian',
          'broadcasting_label' => 'Pengemudi Siaran',
          'broadcasting_placeholder' => 'Pengemudi Siaran',
          'cache_label' => 'Pengandar Tembolok',
          'cache_placeholder' => 'Pengandar Tembolok',
          'session_label' => 'Pengemudi Sesi',
          'session_placeholder' => 'Pengemudi Sesi',
          'queue_label' => 'Sopir Antrian',
          'queue_placeholder' => 'Sopir Antrian',
          'redis_label' => 'Pengemudi Redis',
          'redis_host' => 'Tuan Rumah Redis',
          'redis_password' => 'Kata Sandi Redis',
          'redis_port' => 'Pelabuhan Redis',
          'mail_label' => 'Surat',
          'mail_driver_label' => 'Pengemudi surat',
          'mail_driver_placeholder' => 'Pengemudi Surat',
          'mail_host_label' => 'Tuan Rumah Surat',
          'mail_host_placeholder' => 'Tuan Rumah Surat',
          'mail_port_label' => 'Pelabuhan Surat',
          'mail_port_placeholder' => 'Pelabuhan Surat',
          'mail_username_label' => 'Nama Pengguna Email',
          'mail_username_placeholder' => 'Nama Pengguna Email',
          'mail_password_label' => 'Kata Sandi Surat',
          'mail_password_placeholder' => 'Kata Sandi Surat',
          'mail_encryption_label' => 'Enkripsi Email',
          'mail_encryption_placeholder' => 'Enkripsi Email',
          'pusher_label' => 'pendorong',
          'pusher_app_id_label' => 'Id Aplikasi Pendorong',
          'pusher_app_id_palceholder' => 'Id Aplikasi Pendorong',
          'pusher_app_key_label' => 'Kunci Aplikasi Pendorong',
          'pusher_app_key_palceholder' => 'Kunci Aplikasi Pendorong',
          'pusher_app_secret_label' => 'Rahasia Aplikasi Pendorong',
          'pusher_app_secret_palceholder' => 'Rahasia Aplikasi Pendorong',
        ],
        'buttons' => [
          'setup_database' => 'Siapkan Basis Data',
          'setup_application' => 'Pengaturan Aplikasi',
          'install' => 'Install',
        ],
      ],
    ],
    'classic' => [
      'templateTitle' => 'Langkah 3 | Pengaturan Lingkungan | Editor Klasik',
      'title' => 'Editor Lingkungan Klasik',
      'save' => 'Simpan .env',
      'back' => 'Gunakan Wizard Formulir',
      'install' => 'Simpan dan Instal',
    ],
    'success' => 'Pengaturan file .env Anda telah disimpan.',
    'errors' => 'Tidak dapat menyimpan file .env, Harap buat secara manual.',
    'title' => '',
    'save' => '',
  ],
  'install' => 'Install',
  'installed' => [
    'success_log_message' => 'Penginstal Laravel berhasil DIINSTAL pada',
  ],
  'final' => [
    'title' => 'Instalasi Selesai',
    'templateTitle' => 'Instalasi Selesai',
    'finished' => 'Aplikasi telah berhasil diinstal.',
    'migration' => 'Keluaran Konsol Migrasi &amp; Benih:',
    'console' => 'Keluaran Konsol Aplikasi:',
    'log' => 'Entri Log Instalasi:',
    'env' => 'File .env akhir:',
    'exit' => 'Klik di sini untuk keluar',
  ],
  'updater' => [
    'title' => 'Pembaruan Laravel',
    'welcome' => [
      'title' => 'Selamat Datang di Updater',
      'message' => 'Selamat datang di wizard pembaruan.',
    ],
    'overview' => [
      'title' => 'Ringkasan',
      'message' => 'Ada 1 pembaruan.|Ada pembaruan :number.',
      'install_updates' => 'Instal Pembaruan',
    ],
    'final' => [
      'title' => 'Selesai',
      'finished' => 'Basis data aplikasi telah berhasil diperbarui.',
      'exit' => 'Klik di sini untuk keluar',
    ],
    'log' => [
      'success_message' => 'Penginstal Laravel berhasil DIPERBARUI pada',
    ],
  ],
];