<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Stringd for tool_catmanager
 *
 * @package    tool
 * @subpackage catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$string['pluginname'] = 'Categories Managers';


// Categories.
$string['uploadeditcoursecategories']      = 'Import/Mordifier les catégories de cours';
$string['uploadeditcoursecategories_help'] = "
Les catégories de courses peuvent être ajoutées grâce à un fichier CSV.\n
Vous devez pour cela suivre cette syntaxe : idNumber;name;parent;description\n
-idNumber : l'idNumber de la catégories\n
-name : le nom de la catégories\n
-parent : le parent de la catégories\n
-description : la description de la catégories(optionnel)";

$string['uploadedit']             = 'Importer/Modifier';
$string['uploadcategories']       = 'Importer les catégories ';
$string['uploadsuccess']          = 'Réussi : Les catégories ont été ajoutées';
$string['uploadreturntomainpage'] = 'Retour sur la page principale';
$string['invalidparent']          = 'Identifiant parent invalide';
$string['treatementstop']         = "Pourriez-vous vérifier l'orde de la liste de vos catégories dans le fichier csv ? Le trétement du fichier csv à été arrêter pour éviter tout problème de structuration";
$string['categoriesadded']        = 'Catégories ajoutées :';
$string['categoriesmodified']     = 'Catégories modifiées :';
$string['namerequired']           = 'Nom de catégorie requis';


$string['exportcoursecategories']      = 'Exporter des catégories de cours';
$string['exportcoursecategories_help'] = "Vous pouvez sauvegarder toutes les catégories de cours moodle dans un fichier csv pour le réutiliser sur un autre serveur moodle";
$string['export']                      = 'Exporter';
$string['exportcsv']                   = 'Exporter  fichier CSV les categories de cours';

$string['deletecoursecategories']      = 'Supprimer des categories de cours';
$string['deletecoursecategories_help'] = "
Les catégories de courses peuvent être ajoutées grâce à un fichier CSV.\n
Vous devez pour cela suivre cette syntaxe : idNumber;name;parent;description\n
-idNumber : l'idNumber de la catégories\n
-name : le nom de la catégories\n
-parent : le parent de la catégories\n
-description : la description de la catégories(optionnel)
Attention, si une catégorie contient des cours, tout les cours seront supprimés avec la catégorie!!!!";

$string['delete']                  = 'Supprimer';
$string['deletecategories']        = 'Supprimer des categories';
$string['categoriesdeleted']       = 'Categories supprimées :';
$string['leaveonecategory']        = "
Ceci est la dernière catégories\n
Vous ne pouvez pas supprimer toute les catégories avec ce programme parce que cela est dangereux pour le système, vous devez laissez au moins une catégories.\n
Vous pouvez supprimer la dernière cartégories manuellement, Moodle créera une catégorie par défaut 'Miscellabeous' pour éviter toute erreur. ";
$string['reallyconfirmcategories'] = 'Attention : si une catégorie possède des cours,tout les cours seront suprimés! Voulez-vous vraiment suprimer ?';
$string['defaultcategory']         = 'Ce plugin ne peut pas supprimer la catégorie par défaut pour éviter les erreurs système, vous pouvez le supprimer manuellement';

// Categories.
$string['added']    = 'Ajouté :';
$string['modified'] = 'Modifié :';
$string['deleted']  = 'Supprimé :';

// Categories Manager.
$string['uploadeditmanager']      = 'Importer/Modifier des managers de catégorie';
$string['uploadeditmanager_help'] = "
Les managers de catégories peuvent être ajoutés grâce à un fichier CSV\n
Vous devez suivre la syntaxe suivante: idNumber;userName;role\n
-idNumber: l'idNumber de la catégorie\n
-userName : le nom de l'utiliszteur \n
-role : le rôle à attribué a l'utilisateur ";


$string['uploadeditmanagerhead']         = 'Importer/Modifier';
$string['uploadmanager']                 = 'Importer des manager de catégorie';
$string['uploadmanagersuccess']          = 'Réussi: les managers ont été importés avec succès';
$string['uploadmanagerreturntomainpage'] = 'Retour sur la page principale';
$string['idNumber']                      = 'Le idNumber';
$string['doesnotexist']                  = "n'existe pas";
$string['line']                          = 'ligne';


$string['exportmanager']      = 'Exporter les managers de catégories';
$string['exportmanager_help'] = 'Vous pouvez sauvegarder toutes les managers de catégorie moodle dans un fichier csv pour le réuutiliser sur un autre serveur moodle';
$string['exportmanagerhead']  = 'Exporter';
$string['exportmanagercsv']   = 'Exporter un fichier CSV avec managers de catégories';


$string['deletemanager']         = 'Supprimer des managers de catégories';
$string['deletemanager_help']    = "
Les managers de catégories peuvent être supprimés grâce à un fichier CSV\n
Vous devez suivre la syntaxe suivante: idNumber;userName;role\n
-idNumber: l'idNumber de la catégorie\n
-userName : le nom de l'utiliszteur \n
-role : le rôle à attribué a l'utilisateur ";

$string['deletemanagerhead']     = 'Supprimer';
$string['deletemanagerbutton']   = 'Supprimer managers de categories';
$string['reallyconfirmmanagers'] = 'Voullez-vous vraiment supprimer ?';


// Managers.
$string['newmanagers']           = 'Nouveaux managers :';
$string['newcoursecreators']     = 'Nouveaux créateurs de cours :';
$string['oldmanagers']           = 'anciens managers:';
$string['oldcoursecreators']     = 'Anciens créateurs de cours :';
$string['managersadded']         = 'Managers ajoutés :';
$string['coursecreatorsadded']   = 'Créateurs de cours ajoutés:';
$string['managersdeleted']       = 'Managers supprimés :';
$string['coursecreatorsdeleted'] = 'Créateurs de cours supprimés:';



// General.
$string['writedelete']               = 'Veuillez écrire "DELETE" pour confirmer :';
$string['incorrecttext']             = 'Vous avez entré un texte incorrect';
$string['addtionalinformations']     = 'Informations additionnels (catégorie sans idNumber) :';
$string['recallidnumberemptycreate'] = "Pour la modification,ce plugin ne fonctionne pas avec les catégories sans idNumber pour éviter les collisions puisque le idNumber est ce qui permet d'identifier une catégorie.Si une catégorie n'a pas de idNumber,une nouvelle catégorie sera créée avec les parametres définis.";
$string['recallidnumberemptydelete'] = "Pour la suppression,ce plugin ne fonctionne pas avec les catégories sans idNumber pour éviter les collisions  le idNumber est ce qui permet d'identifier une catégorie. si une catégorie n'a pas de idNumber, you should delete it with the intern interface of Moodle.";
$string['exist']                     = 'existe';
$string['nochanges']                 = 'Aucun changement fait dans la base de données. Peut-être que votre fichier soit vide ou les changements on déjà étaient faits.';
$string['nofile']                    = 'Veuillez insérer un fichier';
$string['reportchanges']             = 'Rapport des chanhements';

$string['badsyntaxcategories'] = "
Vous avez une mauvaise syntaxe.\n
Vous devez suivre la syntaxe suivante : idNumber;name;parent;description\n
-idNumber : l'idNumber de la categorie\n
-name : le nom de la categorie\n
-parent : le parent de la categorie\n
-description : la description de la catagore(optionel);

";

$string['badsyntaxmanager'] = "
Vous avez une mauvaise syntaxe.\n
Vous devez suivre la syntaxe suivante : idNumber;userName;role\n
-idNumber: le idNumber de la categorie\n
-userName : le userName de l'utilisateur\n
-role : le role de l'utilisateur";

$string['error']             = 'Erreurs :';
$string['reportingcreated']  = 'un fichier de rapport a été créé';
$string['downloadreporting'] = 'Télécharger le fichier de rapport';
$string['leaveonecategory']  = "
Ceci est la dernière catégorie\n
Vous ne pouvez pas supprimer toutes les catégories avec ce programme puisque cela est dangereux pour le système, vous devez laisser aumoins une catégorie.\n
Vous pouvez le supprimer manuellement si vous devez vraiment supprimer toutes les catégories, Moodle créera une catégories par défaut'Miscellaneous' pouréviter toute erreur système.";