# Sklep SMS [![Build Status](https://travis-ci.org/TheDoctor0/sklep-sms.png)](https://travis-ci.org/TheDoctor0/sklep-sms) [![StyleCI](https://github.styleci.io/repos/178412640/shield?branch=master&style=flat)](https://github.styleci.io/repos/178412640) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/TheDoctor0/sklep-sms/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/TheDoctor0/sklep-sms/?branch=master) [![License](https://img.shields.io/github/license/TheDoctor0/sklep-sms.svg?color=sucess)](https://img.shields.io/github/license/TheDoctor0/sklep-sms.svg?color=sucess&style=plastic)

Sklep SMS ułatwia zarabianie realnych pieniędzy na własnych serwerach gier!

## Instalacja
Pobierz najnowszy [build.zip](https://github.com/TheDoctor0/sklep-sms/releases/latest).

Postępuj zgodnie z instrukcją dostępną [tutaj](https://sklep-sms.pl/index.php?page=config) pomijając instalację na serwerze.

Następnie pobierz [amxx-182.zip](https://github.com/TheDoctor0/sklep-sms/releases/latest) (dla AMXX 1.8.1-1.8.2) lub [amxx-183.zip](https://github.com/TheDoctor0/sklep-sms/releases/latest) (dla AMXX 1.8.3+) i wgraj na swój serwer.

## Kompatybilność
Na ten moment sklep posiada jedynie plugin AMXX, więc nie jest dostępny na serwery CS:GO (SourceMod).

Sklep był testowany na wersjach:
- AMX Mod X 1.8.1.3746
- AMX Mod X 1.8.2.61
- AMX Mod X 1.9.0.5241

Własna implementacja pluginu stanowiącego silnik sklepu jest w pełni kompatybilna z oryginalnym API i bazą sklepu.
Dodatkowo posiada kilka autorskich usprawnień jak chociażby możliwość sprawdzenia aktywnych usług wraz z datą ich wygaśnięcia z poziomu serwera.

Masz problem z działaniem sklepu z HTTPS? [Zajrzyj tutaj](https://github.com/TheDoctor0/sklep-sms/issues/7#issuecomment-491354212).

## Ograniczenia
Plugin używany na serwerach wykorzystuje moduł Sockets, który nie posiada wsparcia dla protokołu HTTPS.

W przypadku używania HTTPS do obsługi webowej części sklepu (do czego zalecam), należy odkomentować zasady w **.htaccess** (sekcja *Disable HTTTPS For Servers Script*), które wyłączą go dla skryptu *servers_stuff.php* używanego przez plugin jako API.

## Licencja
System licencyjny jest domyślnie wyłączony, więc ze sklepu można korzystać całkowicie za darmo.

Jeśli chcesz wesprzeć autora, rozważ zakup licencji pod adresem: https://sklep.sklep-sms.pl/
