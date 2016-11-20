# EASYMARKETING Gambio Modul
Version 3.0.0 - 20.11.2016

Dieses Modul ist für Gambio ab Version 2.5.x.

## Allgemein
======================

Im Folgenden wird erklärt, wie das Easymarketing-Modul für Gambio installiert wird.

Falls Sie bereits eine alte Version von diesem Modul installiert haben, führen Sie bitte folgende Schritte aus:

1. Deinstallieren Sie das alte Modul im Admin unter `Module` > `Modul-Center`.

3. Leeren Sie den Cache über den Adminbereich

Haben Sie die 2 Schritte durchgeführt, können Sie mit dem nächsten Punkte fortfahren.

## Installation des Moduls
======================

1. [Downloaden Sie das Modul hier](https://github.com/EASYMARKETING/gambio/archive/dev.zip)

2. Dateien in das Hauptverzeichnis vom Shop via FTP übertragen

3. Gehen Sie unter `Module` > `Modul-Center` und installieren Sie hierüber das Easymarketing Modul

## Konfiguration des Moduls
======================

Wenn Sie das Modul installiert haben erscheint der `Bearbeiten`-Button auf welchen Sie draufklicken.

Es öffnet sich das Easymarketing Modul.

Gehen Sie dort auf den oberen Menüpunkt `Einstellungen` und setzen Sie dort die jeweiligen Einstellungen. 

Dort müssen Sie unteranderem auch einen API-Token eintragen, das Sie in Ihrem EASYMARKETING Account finden. Loggen Sie sich dafür bitte bei EASYMARKETING ein und wechseln Sie dann auf `Meine Daten -> API`. Dort finden Sie in der Mitte des Bildschirms in grün: 

`Ihr API Key: a1b1c1d2e32d729adbd656369e28668b`

Dieser Wert ist der API-Token. Kopieren Sie diesen Wert in Ihr Gambio Modul und drücken Sie speichern. Wenn Sie gespeichert haben wird im Hintergrund die automatische Einrichtung und Konfiguration vom Modul durchgeführt.

Ob Sie das Modul und somit den Conversion Tracker erfolgreich installiert haben, sehen Sie im Tab `Übersicht`.

## Für Entwickler
======================

* Im `dev` branch schauen ob es nicht bereits bestehende bug-fixes gibt.

* Im `issue tracker` schauen ob das Feature bzw. der Bug schon behoben wurde.

* Forke das Projekt.

* Starte einen Feature/Bugfix branch.

* Commite so lange bis Du zufrieden bist mit der Arbeit.

* Erstelle einen Pull-Request mit dem erstellten Branch.
