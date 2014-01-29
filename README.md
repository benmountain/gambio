# EASYMARKETING Gambio Module

## Installation des Moduls
======================

1. [Downloaden Sie das Modul hier](https://github.com/EASYMARKETING/gambio/archive/master.zip)

2. Dateien in den shoproot Kopieren

3. SQL ausführen (nur bei Shopsystem modified )

		ALTER TABLE `admin_access` ADD `easymarketing` INT( 1 ) DEFAULT '0' NOT NULL;
		UPDATE `admin_access` SET `easymarketing` = '1' WHERE `customers_id` = '1' LIMIT 1;

## Konfiguration der Endpunkte
======================

Jetzt müssen noch die EASYMARKETING Endpunkte eingetragen werden in Ihrem EASYMARKETING Account. Über die Endpunkte kann EASYMARKETING entsprechende Produkte und Kategorien extrahieren aus Ihrem Shopsystem. Diese Endpunkte sind je Shopsystem unterschiedlich.

Dazu öffnen Sie bitte Ihre API Einstellungen in Ihrem EASYMARKETING Account unter `Meine Daten -> API`

Anmerkung: Bitte verwenden Sie https nur, falls Sie ein vertrauenswürdiges
SSL-Zertifikat besitzen.

Produkte API Endpunkt

	https://domain.tld/api/easymarketing/products.php

Beste Produkte API Endpunkt

	https://domain.tld/api/easymarketing/bestseller.php

Neue Produkte API Endpunkt

	https://domain.tld/api/easymarketing/products.php

Produkt via ID Endpunkt

	https://domain.tld/api/easymarketing/products.php

Kategorien API Endpunkt

	https://domain.tld/api/easymarketing/categories.php
	
**Produkt ID zum testen** 

Hier wird einfach zufällig eine Produkt ID aus Ihrem Shop eingetragen. Diese wird nur zu Test-Zwecken mit angegeben. EASYMARKETING testet dann, ob dieses einzelne Produkt erfolgreich extrahiert werden kann.

Wenn Sie in Ihrem Shop ein Produkt mit der ID `1` haben könnte dies z.B. sein:

	1

**ID der Root Kategorie**

Das ist die ID der höchsten Kategorie in Ihrem Shop. Die `Ober-Kategorie` bzw. `Root-Kategorie` enthält alle Unter-Kategorien Ihres Shopsystem. EASYMARKETING wird dann alle Unter-Kategorien versuchen zu extrahieren. In Ihrem `Kategorie-Verwalter` steht die ID typischerweise in dem Link wenn Sie mit der Maus über die `Ober-Kategorie` navigieren.


**Konfiguration des Shop Token**

Der Shop Token ist ein Passwort Ihres Shops. Dieses Passwort kann auf der Modul-Seite des Plugins definiert werden. EASYMARKETING übermittelt bei jeder Anfrage diesen `Shop Token`. Nur falls der `Shop Token` Ihrem eingegebenem Token entspricht, werden die Anfragen autorisiert. Sie müssen hier also genau den von Ihnen definierten `Shop Token` eingeben.

Beispiel:

Sie haben in Ihrem Backend auf der Modulseite den Token wie folgt definiert:

	  Shop Token: 123123123123
	  
Dann muss genau dieser Token auch in Ihrem EASYMARKETING Account eingegeben werden.


      Shop token: 123123123123
			

Über die Funktion `API Setup testen` kann jetzt überprüft werden ob die API-Endpunkte richtig konfiguriert sind und ob das Plugin richtig aktiviert
wurde. Falls Sie Fehlermeldungen erhalten, kontaktieren Sie uns bitte.

## Installation des Moduls (Optional)

Durch die Implementierung wird ein `facebook like` button im Checkout angezeigt, über den Produkte an Freunde weiter empfohlen werden können.

1. die Datei `function.facebook_badge.php` aus dem addon Ordner in den Smarty plugin Ordner kopieren

2. der Smarty Ordner befindet sich normalerweise in `/includes/classes/Smarty2.x/plugins`

3. Im Template kann nun in den Artikeldetails z.B. `/templates/_dein_template_/module/produtcs_listing/_deine_listing_datei.html` folgendes hier eingefügt werden:

		{facebook_badge products_id=$PRODUCTS_ID}


4. Für den Checkout kann in der Datei `/templates/_dein_template_/module/checkout_success.html` das hier eingefügt werden:

		{facebook_badge}



## Für Entwickler

* Im `master` gucken ob es nicht bereits bestehende bug-fixes gibt.

* Im `issue tracker` gucken ob das Feature bzw. der Bug schon behoben wurde.

* Forke das Projekt.

* Starte einen Feature/Bugfix branch.

* Commite so lange bis Du zufrieden bist mit der Arbeit.

* Erstelle einen Pull-Request mit dem erstellten Branch.
