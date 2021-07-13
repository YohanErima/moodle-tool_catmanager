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
 * Strings for tool_catmanager
 *
 * @package    tool_catmanager
 * @subpackage catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$string['pluginname'] = 'Categories Managers';


// Categories.
$string['uploadeditcoursecategories']      = 'Ajouter/Modifier les catégories de cours';
$string['uploadeditcoursecategories_help'] = "
Les catégories de courses peuvent être ajoutées grâce à un fichier CSV.\n
Vous devez pour cela suivre cette syntaxe : idNumber;name;parent;description\n
-idNumber : l'idNumber de la catégories\n
-name : le nom de la catégories\n
-parent : le parent de la catégories\n
-description : la description de la catégories (optionnel)";

$string['uploadedit']             = 'Ajouter/Modifier';
$string['uploadcategories']       = 'Ajouter les catégories ';
$string['uploadsuccess']          = 'Réussi : les catégories ont été ajoutées.';
$string['uploadreturntomainpage'] = 'Retour sur la page principale';
$string['invalidparent']          = 'Identifiant parent invalide';
$string['treatementstop']         = "Pourriez-vous vérifier l'ordre de la liste de vos catégories dans le fichier CSV ? Le traitement du fichier CSV a été arrêté pour éviter tout problème de structuration";
$string['categoriesadded']        = 'Catégories ajoutées :';
$string['categoriesmodified']     = 'Catégories modifiées :';
$string['namerequired']           = 'Nom de catégorie requis';


$string['exportcoursecategories']      = 'Exporter des catégories de cours';
$string['exportcoursecategories_help'] = "Vous pouvez sauvegarder toutes les catégories de cours Moodle dans un fichier CSV pour le réutiliser sur un autre serveur Moodle.";
$string['export']                      = 'Exporter';
$string['exportcsv']                   = 'Exporter un fichier CSV avec les categories de cours';

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
Vous ne pouvez pas supprimer toutes les catégories avec ce programme parce que cela est dangereux pour le système, vous devez laisser au moins une catégorie.\n
Vous pouvez supprimer la dernière catégorie manuellement, Moodle créera une catégorie par défaut 'Miscellabeous' pour éviter toute erreur. ";
$string['reallyconfirmcategories'] = 'Attention : si une catégorie possède des cours, tout les cours seront supprimés ! Voulez-vous vraiment supprimer ?';
$string['defaultcategory']         = 'Ce plugin ne peut pas supprimer la catégorie par défaut pour éviter les erreurs système, vous pouvez le supprimer manuellement ?';

// Categories.
$string['added']    = 'Ajoutée(s) :';
$string['modified'] = 'Modifiée(s) :';
$string['deleted']  = 'Supprimée(s) :';

// Categories Manager.
$string['uploadeditmanager']      = 'Ajouter/Modifier des managers de catégorie';
$string['uploadeditmanager_help'] = "
Les managers de catégories peuvent être ajoutés grâce à un fichier CSV\n
Vous devez suivre la syntaxe suivante: idNumber;userName;role\n
-idNumber: l'idNumber de la catégorie\n
-userName : le nom de l'utilisateur \n
-role : le rôle à attribué à l'utilisateur ";


$string['uploadeditmanagerhead']         = 'Ajouter/Modifier';
$string['uploadmanager']                 = 'Ajouter des managers de catégorie';
$string['uploadmanagersuccess']          = 'Réussi : les managers ont été importés avec succès';
$string['uploadmanagerreturntomainpage'] = 'Retour sur la page principale';
$string['idNumber']                      = 'Le idNumber';
$string['doesnotexist']                  = "n'existe pas";
$string['line']                          = 'ligne';


$string['exportmanager']      = 'Exporter les managers de catégories';
$string['exportmanager_help'] = 'Vous pouvez sauvegarder toutes les managers de catégorie Moodle dans un fichier CSV pour le réutiliser sur un autre serveur Moodle';
$string['exportmanagerhead']  = 'Exporter';
$string['exportmanagerCSV']   = 'Exporter un fichier CSV avec managers de catégories';


$string['deletemanager']         = 'Supprimer des managers de catégories';
$string['deletemanager_help']    = "
Les managers de catégories peuvent être supprimés grâce à un fichier CSV\n
Vous devez suivre la syntaxe suivante: idNumber;userName;role\n
-idNumber: l'idNumber de la catégorie\n
-userName : le nom de l'utilisateur \n
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
$string['coursecreatorsadded']   = 'Créateurs de cours ajoutés :';
$string['managersdeleted']       = 'Managers supprimés :';
$string['coursecreatorsdeleted'] = 'Créateurs de cours supprimés :';



// General.
$string['writedelete']               = 'Veuillez écrire "DELETE" pour confirmer :';
$string['incorrecttext']             = 'Vous avez entré un texte incorrect';
$string['addtionalinformations']     = 'Informations additionnels (catégorie sans idNumber) :';
$string['recallidnumberemptycreate'] = "Pour la modification, ce plugin ne fonctionne pas avec les catégories sans idNumber pour éviter les collisions puisque le idNumber est ce qui permet d'identifier une catégorie. Si une catégorie n'a pas d'idNumber, une nouvelle catégorie sera créée avec les paramètres définis.";
$string['recallidnumberemptydelete'] = "Pour la suppression,ce plugin ne fonctionne pas avec les catégories sans idNumber pour éviter les collisions  le idNumber est ce qui permet d'identifier une catégorie. Si une catégorie n'a pas d'idNumber, vous devriez le supprimer avec l'interface de Moodle.";
$string['exist']                     = 'existe';
$string['nochanges']                 = 'Aucun changement fait dans la base de données. Peut-être que votre fichier soit vide ou les changements ont déjà été faits.';
$string['nofile']                    = 'Veuillez insérer un fichier';
$string['reportchanges']             = 'Rapport des changements';

$string['badsyntaxcategories'] = "
Vous avez une mauvaise syntaxe.\n
Vous devez suivre la syntaxe suivante : idNumber;name;parent;description\n
-idNumber : l'idNumber de la categorie\n
-name : le nom de la categorie\n
-parent : le parent de la categorie\n
-description : la description de la catagore(optionnel);

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
Vous ne pouvez pas supprimer toutes les catégories avec ce programme puisque cela est dangereux pour le système, vous devez laisser au moins une catégorie.\n
Vous pouvez le supprimer manuellement si vous devez vraiment supprimer toutes les catégories, Moodle créera une catégorie par défaut 'Miscellaneous' pour éviter toute erreur système.";