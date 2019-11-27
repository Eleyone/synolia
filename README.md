# synolia
Synolia test

Exercise repport

- Naviguer sur l'application de demo, tester des création de contact, la suppression (30min)
- lire les guides developpers pour API (45min)
- instancier une appli Symfony 5 (30min)
- tester les appels via Insomnia (outils de debug API rest)
- Créer et Implementer les appel API dans la class SugarAPIAdpater qui sera utilisé comme un service dans SF5 :
    - une méthode auth, pour gérer l'authentification et récupérer le token de connection pour les autre appel (15min)
    - une méthode par appel API specifique (une pour la recherche de contact, une pour la récupération des tickets, et une pour la création de ticket) (15min / methode)
- Implementé 2 routes et methode assoiciées dans le controller ContactsController:
    - une méthode pour afficher la liste des Contacts, qui va utilisé le service SugarAPIAdpater pour trouver les contact (15min)
    - une méthode pour afficher la liste des Tickets associés au contact selectionné. (15min)

Amélioration

- Dans l'idéale, j'aurais pu ajouter une couche d'abstraction sur l'adapter, afin de mutualisé plus de chose
- j'ai mis en dure dans la classe les infos relative à l'api, mais il conviens de les stocker dans de la config lié a l'environement
- la gestion des erreurs, meriterer d'être étoffer, et loger surtout
- ajouter un systeme de templating pour le rendu (twig par exemple)

Incomprhension

Je n'ai pas réussi à me connecter avec l'utilisateur admin comme demandé dans l'exercisse. J'ai d'ailleurs perdu pas mal de temps à chercher dans l'ihm, si je trouvé un moyen de voir les utilisateurs et l'interface admin, que je n'ai pas trouvé. Le fait est que j'était loger avec un user qui apparrament avait tous les droits sur les modules, mais pas sur l'administration. Du coup pour avancer, j'ai pris le partis d'ultiliser les API avec cette utilisateur.

