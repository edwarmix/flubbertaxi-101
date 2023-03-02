<?php 
return [
  'title' => 'Instalator Laravela',
  'next' => 'Następny krok',
  'back' => 'Poprzedni',
  'finish' => 'zainstalować',
  'forms' => [
    'errorTitle' => 'Wystąpiły następujące błędy:',
  ],
  'welcome' => [
    'templateTitle' => 'Powitanie',
    'title' => 'Instalator Laravela',
    'message' => 'Łatwy kreator instalacji i konfiguracji.',
    'next' => 'Sprawdź wymagania',
  ],
  'requirements' => [
    'templateTitle' => 'Krok 1 | Wymagania serwera',
    'title' => 'Wymagania serwera',
    'next' => 'Sprawdź uprawnienia',
  ],
  'permissions' => [
    'templateTitle' => 'Krok 2 | Uprawnienia',
    'title' => 'Uprawnienia',
    'next' => 'Skonfiguruj środowisko',
  ],
  'environment' => [
    'menu' => [
      'templateTitle' => 'Krok 3 | Ustawienia środowiska',
      'title' => 'Ustawienia środowiska',
      'desc' => 'Wybierz, jak chcesz skonfigurować plik <code>.env</code> aplikacji.',
      'wizard-button' => 'Konfiguracja kreatora formularzy',
      'classic-button' => 'Klasyczny edytor tekstu',
    ],
    'wizard' => [
      'templateTitle' => 'Krok 3 | Ustawienia środowiska | Kreator z przewodnikiem',
      'title' => 'Prowadzony kreator <code>.env</code>',
      'tabs' => [
        'environment' => 'Środowisko',
        'database' => 'Baza danych',
        'application' => 'Aplikacja',
      ],
      'form' => [
        'name_required' => 'Wymagana jest nazwa środowiska.',
        'app_name_label' => 'Nazwa aplikacji',
        'app_name_placeholder' => 'Nazwa aplikacji',
        'app_environment_label' => 'Środowisko aplikacji',
        'app_environment_label_local' => 'Lokalny',
        'app_environment_label_developement' => 'Rozwój',
        'app_environment_label_qa' => 'Qa',
        'app_environment_label_production' => 'Produkcja',
        'app_environment_label_other' => 'Inny',
        'app_environment_placeholder_other' => 'Wprowadź swoje środowisko...',
        'app_debug_label' => 'Debugowanie aplikacji',
        'app_debug_label_true' => 'Prawdziwe',
        'app_debug_label_false' => 'Fałszywy',
        'app_log_level_label' => 'Poziom dziennika aplikacji',
        'app_log_level_label_debug' => 'odpluskwić',
        'app_log_level_label_info' => 'informacje',
        'app_log_level_label_notice' => 'zauważyć',
        'app_log_level_label_warning' => 'ostrzeżenie',
        'app_log_level_label_error' => 'błąd',
        'app_log_level_label_critical' => 'krytyczny',
        'app_log_level_label_alert' => 'alarm',
        'app_log_level_label_emergency' => 'nagły wypadek',
        'app_url_label' => 'URL aplikacji',
        'app_url_placeholder' => 'URL aplikacji',
        'db_connection_failed' => 'Nie można połączyć z bazą danych.',
        'db_connection_label' => 'Połączenie z bazą danych',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Host bazy danych',
        'db_host_placeholder' => 'Host bazy danych',
        'db_port_label' => 'Port bazy danych',
        'db_port_placeholder' => 'Port bazy danych',
        'db_name_label' => 'Nazwa bazy danych',
        'db_name_placeholder' => 'Nazwa bazy danych',
        'db_username_label' => 'Nazwa użytkownika bazy danych',
        'db_username_placeholder' => 'Nazwa użytkownika bazy danych',
        'db_password_label' => 'Hasło do bazy danych',
        'db_password_placeholder' => 'Hasło do bazy danych',
        'app_tabs' => [
          'more_info' => 'Więcej informacji',
          'broadcasting_title' => 'Nadawanie, buforowanie, sesja i kolejka',
          'broadcasting_label' => 'Sterownik transmisji',
          'broadcasting_placeholder' => 'Sterownik transmisji',
          'cache_label' => 'Sterownik pamięci podręcznej',
          'cache_placeholder' => 'Sterownik pamięci podręcznej',
          'session_label' => 'Sterownik sesji',
          'session_placeholder' => 'Sterownik sesji',
          'queue_label' => 'Kierowca kolejki',
          'queue_placeholder' => 'Kierowca kolejki',
          'redis_label' => 'Redis kierowca',
          'redis_host' => 'Redis Host',
          'redis_password' => 'Hasło Redis',
          'redis_port' => 'Port Redis',
          'mail_label' => 'Poczta',
          'mail_driver_label' => 'Sterownik poczty',
          'mail_driver_placeholder' => 'Sterownik poczty',
          'mail_host_label' => 'Host poczty',
          'mail_host_placeholder' => 'Host poczty',
          'mail_port_label' => 'Port pocztowy',
          'mail_port_placeholder' => 'Port pocztowy',
          'mail_username_label' => 'Nazwa użytkownika poczty',
          'mail_username_placeholder' => 'Nazwa użytkownika poczty',
          'mail_password_label' => 'Hasło do poczty',
          'mail_password_placeholder' => 'Hasło do poczty',
          'mail_encryption_label' => 'Szyfrowanie poczty',
          'mail_encryption_placeholder' => 'Szyfrowanie poczty',
          'pusher_label' => 'Popychacz',
          'pusher_app_id_label' => 'Identyfikator aplikacji Pusher',
          'pusher_app_id_palceholder' => 'Identyfikator aplikacji Pusher',
          'pusher_app_key_label' => 'Przycisk aplikacji Pusher',
          'pusher_app_key_palceholder' => 'Przycisk aplikacji Pusher',
          'pusher_app_secret_label' => 'Sekret aplikacji Pusher',
          'pusher_app_secret_palceholder' => 'Sekret aplikacji Pusher',
        ],
        'buttons' => [
          'setup_database' => 'Konfiguracja bazy danych',
          'setup_application' => 'Konfiguracja aplikacji',
          'install' => 'zainstalować',
        ],
      ],
    ],
    'classic' => [
      'templateTitle' => 'Krok 3 | Ustawienia środowiska | Edytor klasyczny',
      'title' => 'Klasyczny edytor środowiska',
      'save' => 'Zapisz .env',
      'back' => 'Użyj kreatora formularzy',
      'install' => 'Zapisz i zainstaluj',
    ],
    'success' => 'Twoje ustawienia pliku .env zostały zapisane.',
    'errors' => 'Nie można zapisać pliku .env. Utwórz go ręcznie.',
    'title' => '',
    'save' => '',
  ],
  'install' => 'zainstalować',
  'installed' => [
    'success_log_message' => 'Instalator Laravel pomyślnie ZAINSTALOWANY na',
  ],
  'final' => [
    'title' => 'Instalacja zakończona',
    'templateTitle' => 'Instalacja zakończona',
    'finished' => 'Aplikacja została pomyślnie zainstalowana.',
    'migration' => 'Dane wyjściowe konsoli migracji i nasion:',
    'console' => 'Dane wyjściowe konsoli aplikacji:',
    'log' => 'Wpis dziennika instalacji:',
    'env' => 'Ostateczny plik .env:',
    'exit' => 'Kliknij tutaj, aby wyjść',
  ],
  'updater' => [
    'title' => 'Aktualizator Laravela',
    'welcome' => [
      'title' => 'Witamy w aktualizatorze',
      'message' => 'Witamy w kreatorze aktualizacji.',
    ],
    'overview' => [
      'title' => 'Przegląd',
      'message' => 'Jest 1 aktualizacja.|Istnieje :number aktualizacji.',
      'install_updates' => 'Zainstaluj aktualizacje',
    ],
    'final' => [
      'title' => 'Skończone',
      'finished' => 'Baza danych aplikacji została pomyślnie zaktualizowana.',
      'exit' => 'Kliknij tutaj, aby wyjść',
    ],
    'log' => [
      'success_message' => 'Instalator Laravel został pomyślnie ZAKTUALIZOWANY w dniu',
    ],
  ],
];