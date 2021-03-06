# jemdev\chiffrement\cryptopenssl Chiffrement avec openssl

- Auteur:       Jean Molliné
- Licence:      [CeCILL V2][]
- Pré-Requis: 
  - PHP >= 7.2.0
- Contact :     [Message][]
- GitHub :      [github.com/jemdev/chiffrement][]
- Packagist :   [packagist.org/packages/jemdev/chiffrement][]
****
## Attention, classe jemdev\chiffrement\crypt obsolète
La classe jemdev\chiffrement\crypt de cette librairie est obsolète : l'extension mcrypt devient obsolète à partir de PHP 7.1 et sera retirée dès la version 7.2, ce qui veut dire que cette classe deviendra inutilisable.
Un remplacement par l'utilisation de l'extension OpenSSL est désormais disponible avec la classe jemdev\chiffrement\cryptopenssl dans la même librairie
****
## Nouvelle classe, mais les mêmes méthodes
Les méthodes disponibles sont les mêmes dans les deux classes. Quelques modifications dans la classe chiffrement\crypt ont abouti à la création d'une Interface : le constructeur de chacun des deux retourne donc une interface plutôt que la classe elle-même.

La classe originale a été réalisée suite a un très bon didactitiel d'introduction au chiffrement avec la librairie de chiffrement mcrypt posté par Ripat sur [Lumadis.be][] : le développement de la nouvelle classe cryptopenssl a été fait de sorte que le passage de 'une à l'autre ne requiert que de modifier l'appel du constructeur. 
Toutefois, **attention lors de la transition** : il n'y a pas de *« mode »* dans la classe cryptopenssl contrairement à l'autre s'appuyant sur mcrypt.
****

# Utilisation
La création d'une instance ne change pas spécialement en dehors du paramètre *« mode »* qui a disparu:

    $brouilleur = new cryptopenssl($grainDeSel, $iv = null, $algo = "aes-256-cbc", $locale = 'fr');

 - Le premier paramètre est la clé de chiffrement, obligatoire;
 - Le second est le vecteur d'initialisation : optionnel, s'il est omis, un vecteur aléatoire sera automatiquement généré au cours du processus; On pourra le récupérer pour le déchiffrement avec la méthode *getGrainDeSel()*.
 - L'algorithme à utiliser : optionnel, s'il est omis, on utilisera *aes-256-cbc* par défaut; On peut cependant modifier cet algorithme en passant par la méthode *setAlgo()". Une vérification sera alors automatiquement effectuée pour vérifier l'existence de cet algorithme. Si l'algorithme choisi n'existe pas, une exception sera levée.
 - La langue utilisée : par défaut, ce sera le français, elle indique dans quelle langue doivent être retournés les messages d'erreur s'il y a lieu. Deux langues sont disponible pour l'instant, le français (par défaut) et l'anglais.
****
L'utilisation est des plus simples. À partir d'une instance de *jemdev\chiffrement\cryptopenssl*, il y a principalement deux méthodes à utiliser :

- encrypt(string: $chaine; bool: $encode) : chiffre le texte envoyé en paramètre. La méthode retourne la le texte chiffré. En option, on peut indiquer en second paramètre un booléen : si on indique *true*, la chaîne chiffrée sera en outre encodée en base64.
- decrypt(string: $chaineChiffree; bool: $decode) : déchiffre la chaîne envoyée en paramètre. De la même manière que pour la méthode de chiffrement, un second paramètre optionnel permet d'indiquer s'il faut au préalable décoder la chaîne à déchiffrer si elle a été encodée en base64 lors de son chiffrement.

Le constructeur de la classe ne requiert obligatoirement que le premier paramètre indiquant un *grain-de-sel*, chaîne de caractère qui sera utilisée dans la phase de chiffrement. On peut ensuite indiquer :

- Un vecteur d'initialisation, par défaut *rand* : une chaîne aléatoire sera alors générée;
- L'algorithme de chiffrement : par défaut, on utilisera *aes-256_cbc*;
- La langue utilisée pour les messages d'erreur, affichera les erreurs s'il y a lieu. L'anglais est également disponible (*en*). Optionnel, par défaut *fr*
- Un vecteur d'initialisation pour le déchiffrement, par défaut aucun : dans ce cas, un vecteur aléatoire sera généré.

Utilisant peu cette classe, je ne ferai pas une description beaucoup plus détaillée. Mais la classe est largement commentée, n'hésitez pas à vous référer à ces commentaires directement dans le code pour davantage de précisions sur les détails des méthodes.
****
## Tests
Des tests unitaires ont été introduits avec le développement de la classe *jemdev\chiffrement\cryptopenssl*

****
## Note for english speaking users
My english level is not sufficient to write a good documentation in english. But if you're natively english speaking and understanding well french, please, feel free to write a translation. Enjoy this library.

[CeCILL V2]: http://cecill.info/licences/Licence_CeCILL_V2-fr.html "Texte de la licence CeCILL V2"
[Message]: https://jem-dev.com/contacter-jem-developpement/ "Contacter Jean Molliné via son site"
[github.com/jemdev/chiffrement]: https://github.com/jemdev/chiffrement "Page GitHub de cette classe"
[packagist.org/packages/jemdev/chiffrement]: https://packagist.org/packages/jemdev/chiffrement "Page Packagist de cette classe"
[Lumadis.be]: http://www.lumadis.be/dvp/crypt/tuto_crypt.php "Tutoriel sur le chiffrement avec mcrypt"
