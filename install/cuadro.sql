-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Czas generowania: 12 Mar 2018, 15:56
-- Wersja serwera: 5.7.19
-- Wersja PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `cuadro`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `admin_logs`
--

CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `logged` tinyint(1) NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `admin_session`
--

CREATE TABLE IF NOT EXISTS `admin_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` text COLLATE utf8_polish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `boxes`
--

CREATE TABLE IF NOT EXISTS `boxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `type` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `content` text COLLATE utf8_polish_ci,
  `amount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `slug` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(512) COLLATE utf8_polish_ci NOT NULL,
  `slug` varchar(512) COLLATE utf8_polish_ci NOT NULL,
  `waiting_room` int(1) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `type` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `url` varchar(256) COLLATE utf8_polish_ci NOT NULL,
  `thumb` varchar(256) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci,
  `main_page_date` datetime NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `info`
--

CREATE TABLE IF NOT EXISTS `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `slug` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `page` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `keywords` varchar(512) COLLATE utf8_polish_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `info`
--

INSERT INTO `info` (`id`, `name`, `slug`, `page`, `content`, `keywords`, `description`) VALUES
(1, 'Polityka prywatności', 'polityka-prywatnosci', 'privacy_policy', '<p>Oto nasze stanowisko w sprawie gromadzenia, przetwarzania i wykorzystywania danych, wprowadzonych przez użytkownik&oacute;w serwisu.</p>\r\n\r\n<p>Jaka jest polityka serwisu dotycząca adres&oacute;w e-mail?<br />\r\nPodany podczas dodawania rejestracji adres e-mail służy do weryfikacji osoby zamieszczającej pliki.</p>\r\n\r\n<p>Czy adresy e-mail są udostępniane innym osobom, lub firmom?<br />\r\nNie udostępniamy takich danych&nbsp;osobom trzecim lub firmom. Jednak należy mieć na uwadze, że podane w treści opisu dane mogą zostać &quot;zapamiętane&quot; przez inne osoby lub firmy.&nbsp;Administrator serwisu dołożył należytej staranności w celu odpowiedniego zabezpieczenia przekazanych danych osobowych, a w szczeg&oacute;lności przed ich udostępnieniem osobom nieupoważnionym.</p>\r\n\r\n<p>Ciasteczka (pliki cookie) i sygnalizatory WWW (web beacon)</p>\r\n\r\n<p>Zastrzegamy sobie możliwość do wykorzystania plik&oacute;w cookie (ciasteczek) oraz tzw session storage. Pliki te są zapisywane na Twoim komputerze. Służą one do:</p>\r\n\r\n<p>a) dostosowania zawartości serwisu&nbsp;do preferencji użytkownika oraz optymalizacji korzystania ze stron internetowych;,</p>\r\n\r\n<p>b) utrzymania sesji użytkownika serwisu internetowego (po zalogowaniu), dzięki kt&oacute;rej użytkownik nie musi na każdej podstronie serwisu ponownie wpisywać loginu i hasła,</p>\r\n\r\n<p>c) dostarczania użytkownikom treści reklamowych bardziej dostosowanych do ich zainteresowań.</p>\r\n\r\n<p>Serwis wyświetla reklamy pochodzące od zewnętrznych dostawc&oacute;w. Dostawcy reklam (np. Google) mogą używać ciasteczek i sygnalizator&oacute;w WWW, mogą uzyskać informację o Twoim adresie IP i typie używanej przeglądarki, sprawdzić czy zainstalowany jest dodatek Flash itp. Dzięki ciasteczkom, sygnalizatorom i znajomości adresu IP dostawcy reklam mogą decydować o treści reklam.&nbsp;</p>\r\n\r\n<p>Przeglądarki internetowe, oraz niekt&oacute;re Firewalle, umożliwiają wyłączenie obsługi ciasteczek i sygnalizator&oacute;w, lub jej ograniczenie dla wszystkich lub tylko dla wybranych stron WWW. Jednak wyłączenie obsługi ciasteczek i sygnalizator&oacute;w może uniemożliwić poprawne działanie naszego serwisu.&nbsp;</p>\r\n\r\n<p>Wsp&oacute;łczesne przeglądarki umożliwiają przeglądanie stron www tzw. &quot;trybie prywatnym (incognito)&quot; co zazwyczaj oznacza, że wszystkie odwiedzone strony www nie zostaną zapamiętane w lokalnej historii przeglądarki, a pobrane ciasteczka zostaną skasowane po zakończeniu pracy z przeglądarką. Szczeg&oacute;łowy opis &quot;trybu prywatnego&quot; jest dostępny w pomocy przeglądarki.</p>\r\n\r\n<p>Wyłączenie &quot;ciasteczek&quot; w najbardziej popularnych przeglądarkach:</p>\r\n\r\n<p><strong>Google Chrome</strong></p>\r\n\r\n<p>Należy kliknąć na menu (w prawym g&oacute;rnym rogu), zakładka Ustawienia &gt; Pokaż ustawienia zaawansowane. W sekcji &bdquo;Prywatność&rdquo; trzeba kliknąć przycisk Ustawienia treści. W sekcji &bdquo;Pliki cookie&rdquo; można zmienić następujące ustawienia plik&oacute;w Cookie:</p>\r\n\r\n<ul>\r\n	<li>Usuwanie plik&oacute;w Cookie,</li>\r\n	<li>Domyślne blokowanie plik&oacute;w Cookie,</li>\r\n	<li>Domyślne zezwalanie na pliki Cookie,</li>\r\n	<li>Domyślne zachowywanie plik&oacute;w Cookie i danych stron do zamknięcia przeglądarki</li>\r\n	<li>Określanie wyjątk&oacute;w dla plik&oacute;w Cookie z konkretnych witryn lub domen</li>\r\n</ul>\r\n\r\n<p><strong>Mozilla Firefox</strong></p>\r\n\r\n<p>Z menu przeglądarki: Narzędzia &gt; Opcje &gt; Prywatność. Uaktywnić pole Program Firefox: &bdquo;będzie używał ustawień użytkownika&rdquo;. O ciasteczkach (cookies) decyduje zaznaczenie &ndash; bądź nie &ndash; pozycji Akceptuj ciasteczka.</p>\r\n\r\n<p><strong>Opera</strong></p>\r\n\r\n<p>Z menu przeglądarki: Narzędzie &gt; Preferencje &gt; Zaawansowane. O ciasteczkach decyduje zaznaczenie &ndash; bądź nie &ndash; pozycji Ciasteczka.</p>\r\n\r\n<p><strong>Safari</strong></p>\r\n\r\n<p>W menu rozwijanym Safari trzeba wybrać Preferencje i kliknąć ikonę Bezpieczeństwo.<br />\r\nW tym miejscu wybiera się poziom bezpieczeństwa w obszarze ,,Akceptuj pliki cookie&rdquo;.</p>\r\n', '', ''),
(2, 'Regulamin', 'regulamin', 'rules', '', '', ''),
(3, 'Kontakt', 'kontakt', 'contact', '', '', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logs_files`
--

CREATE TABLE IF NOT EXISTS `logs_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logs_mails`
--

CREATE TABLE IF NOT EXISTS `logs_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `action` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logs_users`
--

CREATE TABLE IF NOT EXISTS `logs_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mails`
--

CREATE TABLE IF NOT EXISTS `mails` (
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `full_name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `subject` text COLLATE utf8_polish_ci NOT NULL,
  `message` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `mails`
--

INSERT INTO `mails` (`name`, `full_name`, `subject`, `message`) VALUES
('contact_form', 'Contact form', 'Wiadomość z formularza kontaktowego strony {title}', '<p>Witaj!</p>\r\n\r\n<p>Została do Ciebie wysłana wiadomość z formularza kontaktowego ze strony {base_url}</p>\r\n\r\n<p>Nadawca: {name}</p>\r\n\r\n<p>Adres email: {email}</p>\r\n\r\n<p>Wiadomość: {message}</p>\r\n'),
('profile', 'Profile', 'Wiadomość do profilu {username}', '<p>Witaj!</p>\r\n\r\n<p>Została do Ciebie wysłana wiadomość ze strony&nbsp;<a href=\"{base_url}\">{base_url}</a>&nbsp;ze strony Twojego profilu {username}</p>\r\n\r\n<p>Nadawca: {name}</p>\r\n\r\n<p>Adres email: {email}</p>\r\n\r\n<p>Wiadomość: {message}</p>\r\n'),
('register', 'Register', 'Witamy na stronie {title}', '<p>Witaj na stronie <a href=\"{base_url}\">{title}</a>!<br />\r\n<br />\r\nDziękujemy za rejestrację.<br />\r\n<br />\r\nŻeby ją dokończyć kliknij w link: <a href=\"{activation_link}\">{activation_link}</a><br />\r\n<br />\r\nInformujemy że link aktywacyjny jest ważny 24 godziny, po tym czasie nieaktywowane konta zostają usuwane.<br />\r\nJeśli to nie Ty się rejestrowałeś to zignoruj tą wiadomość<br />\r\n<br />\r\nPozdrawiamy<br />\r\n{title}<br />\r\n<br />\r\n<a href=\"{base_url}\">{link_logo}</a></p>\r\n'),
('register_fb', 'Register by Facebook', 'Witamy na stronie {title}', '<p>Witaj na stronie <a href=\"{base_url}\">{title}</a>!<br />\r\n<br />\r\nDziękujemy za rejestrację poprzez konto Facebook.</p>\r\n\r\n<p>Twoje losowo wygenerowane hasło to: {password}<br />\r\n<br />\r\nPozdrawiamy<br />\r\n{title}<br />\r\n<br />\r\n<a href=\"{base_url}\">{link_logo}</a></p>\r\n'),
('register_google', 'Register by Google', 'Witamy na stronie {title}', '<p>Witaj na stronie <a href=\"{base_url}\">{title}</a>!<br />\r\n<br />\r\nDziękujemy za rejestrację poprzez konto Google.</p>\r\n\r\n<p>Twoje losowo wygenerowane hasło to: {password}<br />\r\n<br />\r\nPozdrawiamy<br />\r\n{title}<br />\r\n<br />\r\n<a href=\"{base_url}\">{link_logo}</a></p>\r\n'),
('reset_password', 'Reset password', 'Reset hasła - {title}', '<p>Witaj {username}!<br />\r\n<br />\r\nAby zresetować swoje hasło do serwisu <a href=\"{base_url}\">{title}</a> kliknij w następujący link: <a href=\"{reset_password_link}\">{reset_password_link}</a><br />\r\n<br />\r\nPozdrawiamy<br />\r\n{title}</p>\r\n');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mails_queue`
--

CREATE TABLE IF NOT EXISTS `mails_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `action` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `data` text COLLATE utf8_polish_ci NOT NULL,
  `priority` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reset_password`
--

CREATE TABLE IF NOT EXISTS `reset_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `used` int(1) DEFAULT NULL,
  `active` int(1) NOT NULL,
  `code` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `session_user`
--

CREATE TABLE IF NOT EXISTS `session_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `code` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `value` text COLLATE utf8_polish_ci,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('add_file_not_logged', '1'),
('ads_amount_files', '0'),
('ads_between_files', '0'),
('allow_comments_fb_file', '1'),
('allow_comments_fb_profile', '1'),
('analytics', ''),
('automation_added', '0'),
('automation_added_days', '0'),
('automation_added_files', '0'),
('automation_randomly', '0'),
('automation_randomly_files', '0'),
('automation_votes_minus', '0'),
('automation_votes_minus_files', '0'),
('automation_votes_minus_votes', '0'),
('automation_votes_plus', '0'),
('automation_votes_plus_files', '0'),
('automation_votes_plus_votes', '0'),
('base_url', ''),
('black_list_email', ''),
('black_list_ip', ''),
('black_list_words', ''),
('check_ip_user', '1'),
('code_body', ''),
('code_head', ''),
('code_style', ''),
('description', ''),
('email', ''),
('facebook_api', ''),
('facebook_lang', 'pl_PL'),
('facebook_login', '0'),
('facebook_secret', ''),
('facebook_side_panel', ''),
('favicon', '/upload/images/favicon.png'),
('footer_bottom', '<p>Strona pokazowa skryptu dla zdjęć i film&oacute;w Cuadro</p>\r\n'),
('footer_text', '<p><small>Web script for pictures and movies Cuadro v1.8 Project 2018 - 2021 by <a href=\"https://itworksbetter.net\" target=\"_blank\" title=\"Creating websites\">IT Works Better</a></small></p>'),
('footer_top', '<p>CUADRO to uniwersalny skrypt dla stron internetowych z obrazkami i filmami video. Umożliwia on użytkownikom dodawanie własnych zdjęć z dysku, film&oacute;w z serwis&oacute;w Youtube, Vimeo oraz Dailymotion a także upload film&oacute;w MP4.&nbsp;</p>\r\n\r\n<p>Do skryptu dołączony jest Panel Administratora umożliwiający łatwą edycję ustawień strony.&nbsp;<br />\r\nPanel jest dostępny pod adresem: <a href=\"https://cuadro.itworksbetter.net/admin\" style=\"color:#dfdfdf\" target=\"_blank\">https://cuadro.itworksbetter.net/admin</a><br />\r\nLogin: test<br />\r\nHasło: 1234</p>\r\n\r\n<p>To jest strona pokazowa skryptu - służy jedynie pokazaniu jego funkcjonalności.</p>\r\n'),
('generate_sitemap', '1'),
('google_id', ''),
('google_login', '0'),
('google_secret', ''),
('header', 'CUADRO'),
('keywords', ''),
('lang', 'pl'),
('limit_page', '10'),
('limit_page_profile', '10'),
('logo', '/upload/images/logo.png'),
('logo_facebook', '/upload/images/logo.png'),
('mail_attachment', '1'),
('number_char_description', '1024'),
('number_char_title', '128'),
('photo_max_height', '0'),
('photo_max_width', '0'),
('photo_quality', '70'),
('rss', '1'),
('rodo_alert', '0'),
('rodo_alert_text', '<p>Szanowny użytkowniku,<br />\r\npragniemy Cię poinformować, że nasz serwis internetowy może personalizować treści marketingowe do Twoich potrzeb. W związku z tym danymi osobowymi, kt&oacute;re przetwarzamy są np. Tw&oacute;j adres IP, dane pozyskiwane na podstawie plik&oacute;w cookies lub podobnych mechanizm&oacute;w na Twoim urządzeniu o ile pozwolą one na zidentyfikowanie Ciebie.&nbsp;<br />\r\nJeżeli klikniesz przycisk &bdquo;Wyrażam zgodę na przetwarzanie moich danych osobowych&rdquo; lub zamkniesz to okno, to wyrazisz zgodę na przetwarzanie Twoich danych przez właściciela witryny i jego zaufanych partner&oacute;w.<br />\r\nWyrażenie zgody jest dobrowolne. Masz prawo do: dostępu do Twoich danych, ich sprostowania oraz usunięcia. Więcej informacji odnośnie przetwarzania danych osobowych znajdziesz w naszej <a href=\"/info/1,polityka-prywatnosci\">Polityce Prywatności.</a></p>\r\n\r\n<p>Lista zaufanych partner&oacute;w:<br />\r\nGoogle - na stronie są zamieszczone kody reklam Adsense oraz Google Analytics, kt&oacute;re mają na celu wyświetlanie spersonalizowanych treści oraz zbieranie informacji o zachowaniu użytkownika w celu poprawy strony.<br />\r\nFacebook - na stronie zamieszczony jest kod Facebook mający na celu wyświetlanie boksu z komentarzami oraz panelu bocznego.</p>\r\n'),
('show_contact_form_profile', '1'),
('smtp', '0'),
('smtp_host', ''),
('smtp_mail', ''),
('smtp_password', ''),
('smtp_port', '465'),
('smtp_secure', 'ssl'),
('smtp_user', ''),
('social_facebook', '1'),
('social_pinterest', '1'),
('social_twitter', '1'),
('social_wykop', '1'),
('template', 'default'),
('template_color', 'default'),
('title', 'Cuadro - skrypt strony z obrazkami i filmami'),
('url_facebook', ''),
('url_privacy_policy', 'polityka-prywatnosci'),
('url_rules', 'regulamin'),
('video_max_size', '0'),
('voice_only_logged', '0'),
('watermark', '/upload/images/watermark.png'),
('watermark_add', '1');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `slug` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tags_files`
--

CREATE TABLE IF NOT EXISTS `tags_files` (
  `tag_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  KEY `tag_id` (`tag_id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `active` int(1) NOT NULL,
  `moderator` int(1) DEFAULT NULL,
  `activation_code` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `register_fb` int(1) DEFAULT NULL,
  `register_google` int(1) DEFAULT NULL,
  `register_ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `activation_date` datetime DEFAULT NULL,
  `activation_ip` varchar(40) COLLATE utf8_polish_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `voices`
--

CREATE TABLE IF NOT EXISTS `voices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `voice` int(1) NOT NULL,
  `ip` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD KEY `slug` (`slug`);

--
-- Indeksy dla tabeli `files`
--
ALTER TABLE `files`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `waiting_room` (`waiting_room`);

--
-- Indeksy dla tabeli `logs_files`
--
ALTER TABLE `logs_files`
  ADD KEY `file_id` (`file_id`);

--
-- Indeksy dla tabeli `voices`
--
ALTER TABLE `voices`
  ADD KEY `voices_ibfk_1` (`file_id`),
  ADD KEY `voice` (`voice`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
