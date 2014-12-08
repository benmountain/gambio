# EASYMARKETING Gambio Module
Version 2.0.1

Dieses Modul ist nur für Gambio Version 2.1.x.
Falls Sie Gambio mit der Version 2.0.x verwenden, so [erhalten Sie das passende Modul hier](https://github.com/EASYMARKETING/gambio/tree/v2.0.x).

## Allgemein
======================

Im Folgenden wird erklärt, wie das Easymarketing-Modul für Gambio installiert wird.

Falls Sie bereits eine alte Version von diesem Modul installiert haben, führen Sie bitte folgende Schritte aus:

1. Deinstallieren Sie das alte Modul im Admin unter `Module` > `Easymarketing` im Tab `Konfiguration`.

2. Löschen Sie folgende Dateien von Ihrem Server, falls noch eine ältere Version vom Modul verwendet wird:
   - system/overloads/ApplicationBottomExtenderComponent/EasymarketingExtender.inc.php

3. Leeren Sie den Cache über den Adminbereich

Haben Sie die 2 Schritte durchgeführt, können Sie mit dem nächsten Punkte fortfahren.

## Installation des Moduls
======================

1. [Downloaden Sie das Modul hier](https://github.com/EASYMARKETING/gambio/archive/v2.1.x.zip)

2. Dateien in das Hauptverzeichnis vom Shop via FTP übertragen

3. SQL ausführen (Im Admin unter `Toolbox->SQL`)

		ALTER TABLE `admin_access` ADD `easymarketing` INT( 1 ) DEFAULT '0' NOT NULL;
		UPDATE `admin_access` SET `easymarketing` = '1' WHERE `customers_id` = '1' LIMIT 1;

## Konfiguration des Moduls
======================

Wenn Sie das Modul installiert haben erscheint im Admin-Bereich ein neuer Menüpunkt unter `Module->Easymarketing`

Dort müssen Sie das ein Access-Token eintragen, das Sie in Ihrem EASYMARKETING Account finden. Loggen Sie sich dafür bitte bei EASYMARKETING ein und wechseln Sie dann auf `Meine Daten -> API`. Dort finden Sie in der Mitte des Bildschirms in grün: 

`Ihr API Key: a1b1c1d2e32d729adbd656369e28668b`

Dieser Wert ist der Access-Token. Kopieren Sie diesen Wert in Ihr Gambio Modul und drücken Sie speichern. Wenn Sie gespeichert haben wird im Hintergrund die automatische Einrichtung und Konfiguration vom Modul durchgeführt.

Ob Sie das Modul und somit den Conversion Tracker erfolgreich installiert haben, sehen Sie im Tab `Übersicht`.

## Für Entwickler
======================

* Im `master` gucken ob es nicht bereits bestehende bug-fixes gibt.

* Im `issue tracker` gucken ob das Feature bzw. der Bug schon behoben wurde.

* Forke das Projekt.

* Starte einen Feature/Bugfix branch.

* Commite so lange bis Du zufrieden bist mit der Arbeit.

* Erstelle einen Pull-Request mit dem erstellten Branch.
