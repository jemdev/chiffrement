# jemdev\chiffrement Chiffrement avec mcrypt

- Auteur:       Jean Molliné
- Licence:      [CeCILL V2][]
- Pré-Requis: 
  - PHP >= 5.4
- Contact :     [Message][]
- GitHub :      [github.com/jemdev/chiffrement][]
- Packagist :   [packagist.org/packages/jemdev/chiffrement][]
****
Permet une utilisation facile de la librairie mcrypt.

Classe réalisée suite a un très bon didactitiel d'introduction au chiffrage avec la librairie de chiffrement mcrypt posté par Ripat sur [Lumadis.be][]
****

# Utilisation
L'utilisation est des plus simples. À partir d'une instance de *jemdev\chiffrement\crypt*, il y a principalement deux méthodes à utiliser :

- encrypt() : chiffre le texte envoyé en paramètre. La méthode retourne la le texte chiffré. En option, on peut indiquer en second paramètre un booléen : si on indique *true*, la chaine chiffrée sera en outre encodée en base64.
- decrypt() : déchiffre la chaine envoyée en paramètre. De la même manière que pour la méthode de chiffrement, un second paramètre optionnel permet d'indiquer s'il faut au préalable décoder la chaine à déchiffrer si elle a été encodée en base64 lors de son chiffrement.

Le constructeur de la classe ne requiert que le premier paramètre indiquant un *grain-de-seul*, chaine de caractère qui sera utilisée dans la phase de chiffrement. On peut ensuite indiquer :

- l'algorithme de chiffrement : par défaut, on utiliser *rijndael-256*;
- Le mode de chiffrement : par défaut, *nofb*;
- Un vecteur d'initialisation, par défaut *rand*;
- La langue utilisée pour les messages d'erreur, par défaut *fr* affichera les erreur en français. L'anglais est également disponible (*en*).
- Un vecteur d'initialisation pour le déchiffrement, par défaut aucun.

Utilisant peu cette classe, je ne ferai pas une description beaucoup plus détaillée. Mais la classe est largement commentée, n'hésitez pas à vous référer à ces commentaires directement dans le code pour davantage de précisions sur les détails des méthodes.

****
## Note for english speaking users
My english level is not sufficient to write a good documentation in english. But if you're natively english speaking and understanding well french, please, feel free to write a translation. Enjoy this library.

[CeCILL V2]: http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html "Texte de la licence CeCILL V2"
[Message]: http://jem-dev.com/a-propos/contacter-jemdev/ "Contacter Jean Molliné via son site"
[github.com/jemdev/chiffrement]: https://github.com/jemdev/chiffrement "Page GitHub de cette classe"
[packagist.org/packages/jemdev/chiffrement]: https://packagist.org/packages/jemdev/chiffrement "Page Packagist de cette classe"
[Lumadis.be]: http://www.lumadis.be/dvp/crypt/tuto_crypt.php "Tutoriel sur le chiffrement avec mcrypt"