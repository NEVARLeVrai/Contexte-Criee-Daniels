<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>  
<section id="connexion_et_inscription" class="connexion_et_inscription">
            <h2><i class="fas fa-bullhorn"></i> Les Annonces de la Criée de Poulgoazec</h2>
            
            <?php
                // Définir le fuseau horaire à Paris
                date_default_timezone_set('Europe/Paris');
                
                // Afficher les messages de succès ou d'erreur
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert '.($_SESSION['message'] === "L'annonce a été supprimée avec succès." ? 'alert-success' : 'alert-danger').'">';
                    echo $_SESSION['message'];
                    echo '</div>';
                    unset($_SESSION['message']); // Effacer le message après l'avoir affiché
                }

                include "application/config/database.php";

                // Vérifier si l'utilisateur est connecté
                if (isset($_SESSION['identifiant'])) {
                    // Récupérer le type de compte de l'utilisateur
                    $idCompte = $_SESSION['identifiant'];
                    $selectTypeCompte = "SELECT typeCompte FROM COMPTE WHERE idCompte = :idCompte";
                    $stmt = $pdo->prepare($selectTypeCompte);
                    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Vérifier si les informations du compte ont été trouvées
                    if ($row === false) {
                        echo "<p>Erreur : Impossible de récupérer les informations du compte. L'utilisateur n'existe peut-être plus. Veuillez vous reconnecter.</p>";
                        echo '<form method="POST" action="' . site_url('welcome/contenu/Connexion') . '">
                                <br><button type="submit" class="btn">Se reconnecter</button>
                              </form>';
                    } else {
                        // Si les informations sont trouvées, continuer la logique existante
                        if ($row['typeCompte'] === 'acheteur') {
                            // Si l'utilisateur est un acheteur, afficher les annonces
                            $selectAnnonces = "SELECT DISTINCT a.idImage, a.idBateau, a.datePeche, a.idLot, a.prixEnchere, 
                                             a.DateEnchere, a.titreAnnonce, a.idCompteV, a.idCompteA, 
                                             a.dateDerniereEnchere, a.dateFinEnchere,
                                             l.prixPlancher, l.prixEncheresMax
                                             FROM ANNONCE a
                                             JOIN LOT l ON a.idLot = l.idLot AND a.idBateau = l.idBateau AND a.datePeche = l.datePeche
                                             GROUP BY a.idBateau, a.datePeche, a.idLot
                                             ORDER BY a.DateEnchere DESC";
                            $stmt = $pdo->prepare($selectAnnonces);
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            
                            if(count($rows) > 0) {
                                echo '<table>
                                    <thead>
                                        <tr>
                                            <th scope="col">Image</th>                                        
                                            <th scope="col">Bateau</th>
                                            <th scope="col">Date de pêche</th>
                                            <th scope="col">Lot n°</th>
                                            <th scope="col">Prix actuel</th>
                                            <th scope="col">Prix plancher</th>
                                            <th scope="col">Prix max</th>
                                            <th scope="col">Date Enchère</th>
                                            <th scope="col">Titre</th>
                                            <th scope="col">Vendeur</th>
                                            <th scope="col">Dernier enchérisseur</th>
                                            <th scope="col">Date dernière enchère</th>
                                            <th scope="col">Date limite</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Info paiement</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                foreach ($rows as $row) {
                                    echo '<tr>';
                                    echo '<td><img src="../../assets/'.$row['idImage'].'"></td>';
                                    echo '<td>'.$row['idBateau'].'</td>';
                                    echo '<td>'.$row['datePeche'].'</td>';
                                    echo '<td>'.$row['idLot'].'</td>';
                                    echo '<td>'.$row['prixEnchere'].' €</td>';
                                    echo '<td>'.$row['prixPlancher'].' €</td>';
                                    echo '<td>'.($row['prixEncheresMax'] ?? 'Non défini').' €</td>';
                                    echo '<td>'.$row['DateEnchere'].'</td>';
                                    echo '<td>'.$row['titreAnnonce'].'</td>';
                                    echo '<td>'.$row['idCompteV'].'</td>';
                                    echo '<td>'.($row['idCompteA'] ?? 'Aucun').'</td>';
                                    echo '<td>'.($row['dateDerniereEnchere'] ?? 'Aucune').'</td>';
                                    echo '<td>'.($row['dateFinEnchere'] ?? 'Non définie').'</td>';
                                    echo '<td>';
                                    
                                    // Vérifier si l'enchère est terminée et si l'utilisateur connecté est le gagnant
                                    $timezone = new DateTimeZone('Europe/Paris');
                                    $dateEnchere = new DateTime($row['DateEnchere'], $timezone);
                                    $dateFinEnchere = new DateTime($row['dateFinEnchere'], $timezone);
                                    $maintenant = new DateTime('now', $timezone);
                                    
                                    // Calculer la limite de temps pour payer (15 minutes après la fin)
                                    $limitePaiement = clone $dateFinEnchere;
                                    $limitePaiement->modify('+5 minutes');
                                    
                                    // On vérifie que :
                                    // 1. La date d'enchère est passée
                                    // 2. On est avant la limite de paiement
                                    // 3. L'utilisateur est le gagnant
                                    if ($dateEnchere < $maintenant && 
                                        $maintenant < $limitePaiement && 
                                        $row['idCompteA'] === $_SESSION['identifiant'] &&
                                        $dateFinEnchere < $maintenant) {
                                        $urlPaiement = site_url('welcome/contenu/Paiement').'?idLot='.$row['idLot'].'&idBateau='.$row['idBateau'].'&datePeche='.urlencode($row['datePeche']);
                                        echo '<a href="'.$urlPaiement.'" class="btn btn-success">Payer</a>';
                                    }
                                    elseif ($row['idCompteA'] !== $_SESSION['identifiant']) {
                                        echo 'Vous n\'êtes pas le gagnant de cette enchère';
                                    }
                                    elseif ($maintenant < $dateEnchere) {
                                        echo 'L\'enchère n\'a pas encore commencé';
                                    }
                                    elseif ($maintenant > $limitePaiement) {
                                        echo 'Le délai de paiement est dépassé';
                                    }
                                    elseif ($maintenant < $dateFinEnchere) {
                                        echo 'L\'enchère est encore en cours';
                                    }

                                    // Ajouter le bouton Supprimer si l'utilisateur est le vendeur
                                    if ($row['idCompteV'] === $_SESSION['identifiant']) {
                                        // Si le bouton Supprimer a été cliqué
                                        if(isset($_GET['action']) && $_GET['action'] === 'supprimer' 
                                           && isset($_GET['idLot']) && $_GET['idLot'] === $row['idLot']
                                           && isset($_GET['idBateau']) && $_GET['idBateau'] === $row['idBateau']
                                           && isset($_GET['datePeche']) && $_GET['datePeche'] === $row['datePeche']) {
                                            
                                            // Supprimer l'image si elle existe
                                            if($row['idImage']) {
                                                $cheminImage = $_SERVER['DOCUMENT_ROOT'] . "/codeIgniter318-Criee/assets/" . $row['idImage'];
                                                if(file_exists($cheminImage)) {
                                                    unlink($cheminImage);
                                                }
                                            }

                                            $deleteAnnonce = "DELETE FROM ANNONCE WHERE idLot = :idLot AND idBateau = :idBateau AND datePeche = :datePeche AND idCompteV = :idCompteV";
                                            $stmt = $pdo->prepare($deleteAnnonce);
                                            $stmt->bindParam(':idLot', $row['idLot'], PDO::PARAM_INT);
                                            $stmt->bindParam(':idBateau', $row['idBateau'], PDO::PARAM_STR);
                                            $stmt->bindParam(':datePeche', $row['datePeche'], PDO::PARAM_STR);
                                            $stmt->bindParam(':idCompteV', $_SESSION['identifiant'], PDO::PARAM_STR);
                                            
                                            if($stmt->execute()) {
                                                $_SESSION['message'] = "L'annonce et son image ont été supprimées avec succès.";
                                                echo '<script>window.location.href = "'.site_url('welcome/contenu/Annonces').'";</script>';
                                                exit;
                                            }
                                        }
                                        
                                        echo '<br>';
                                        // Afficher le bouton Supprimer
                                        $urlSuppression = site_url('welcome/contenu/Annonces').'?action=supprimer&idLot='.$row['idLot'].'&idBateau='.$row['idBateau'].'&datePeche='.urlencode($row['datePeche']);
                                        echo '<a onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette annonce ?\')" href="'.$urlSuppression.'" class="btn">Supprimer</a>';
                                    }
                                    echo '</td>';
                                    
                                    // Colonne Infos Paiement
                                    echo '<td style="text-align: left; font-size: 0.8em;">';
                                    if ($row['idCompteA'] === $_SESSION['identifiant']) {
                                        if ($maintenant < $dateFinEnchere) {
                                            echo 'Disponible après la fin de l\'enchère<br><br>';
                                            echo 'Fin de l\'enchère : ' . $dateFinEnchere->format('d/m/Y H:i:s') . '<br>';
                                            echo 'Disponible jusqu\'au : ' . $limitePaiement->format('d/m/Y H:i:s');
                                        } elseif ($maintenant > $limitePaiement) {
                                            echo 'Délai de paiement dépassé<br>';
                                            echo 'Limite dépassée le : ' . $limitePaiement->format('d/m/Y H:i:s');
                                        } else {
                                            echo 'Temps restant pour payer : ';
                                            $tempsRestant = $maintenant->diff($limitePaiement);
                                            echo $tempsRestant->format('%i min %s sec');
                                        }
                                    } else {
                                        if ($maintenant < $dateFinEnchere) {
                                            echo 'Enchère en cours jusqu\'au :<br>' . $dateFinEnchere->format('d/m/Y H:i:s');
                                        } elseif ($maintenant > $limitePaiement) {
                                            echo 'Enchère terminée';
                                        } else {
                                            echo 'En attente de paiement';
                                        }
                                    }
                                    echo '</td>';
                                    
                                    echo '</tr>';
                                }
                                echo '</tbody></table>';

                                echo '<br>
                                <p>Vous allez être rediriger pour enchérir
                                      <br>
                                        <form method="POST" action="' . site_url('welcome/contenu/Annonces_Encherir') . '">
                                            <button type="submit" class="btn">Enchérir</button>
                                        </form>
                                    </p>';

                            } else {
                                echo "Aucune annonce n'est disponible pour le moment.";
                            }
                        } elseif ($row['typeCompte'] === 'vendeur') {
                            // Si l'utilisateur est un vendeur, afficher les annonces
                            $selectAnnonces = "SELECT DISTINCT a.idImage, a.idBateau, a.datePeche, a.idLot, a.prixEnchere, 
                                             a.DateEnchere, a.titreAnnonce, a.idCompteV, a.idCompteA, 
                                             a.dateDerniereEnchere, a.dateFinEnchere,
                                             l.prixPlancher, l.prixEncheresMax
                                             FROM ANNONCE a
                                             JOIN LOT l ON a.idLot = l.idLot AND a.idBateau = l.idBateau AND a.datePeche = l.datePeche
                                             WHERE a.idCompteV = :idCompte
                                             GROUP BY a.idBateau, a.datePeche, a.idLot
                                             ORDER BY a.DateEnchere DESC";
                            $stmt = $pdo->prepare($selectAnnonces);
                            $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            
                            if(count($rows) > 0) {
                                echo '<br>
                                <form method="POST" action="' . site_url('welcome/contenu/Annonces_Creation') . '">
                                <button type="submit" class="btn">Créer une annonce</button>
                                </form>';

                                echo '<table>
                                    <thead>
                                        <tr>
                                            <th scope="col">Image</th>                                        
                                            <th scope="col">Bateau</th>
                                            <th scope="col">Date de pêche</th>
                                            <th scope="col">Lot n°</th>
                                            <th scope="col">Prix actuel</th>
                                            <th scope="col">Prix plancher</th>
                                            <th scope="col">Prix max</th>
                                            <th scope="col">Date Enchère</th>
                                            <th scope="col">Titre</th>
                                            <th scope="col">Vendeur</th>
                                            <th scope="col">Dernier enchérisseur</th>
                                            <th scope="col">Date dernière enchère</th>
                                            <th scope="col">Date limite</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Info paiement</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                foreach ($rows as $row) {
                                    echo '<tr>';
                                    echo '<td><img src="../../assets/'.$row['idImage'].'"></td>';
                                    echo '<td>'.$row['idBateau'].'</td>';
                                    echo '<td>'.$row['datePeche'].'</td>';
                                    echo '<td>'.$row['idLot'].'</td>';
                                    echo '<td>'.$row['prixEnchere'].' €</td>';
                                    echo '<td>'.$row['prixPlancher'].' €</td>';
                                    echo '<td>'.($row['prixEncheresMax'] ?? 'Non défini').' €</td>';
                                    echo '<td>'.$row['DateEnchere'].'</td>';
                                    echo '<td>'.$row['titreAnnonce'].'</td>';
                                    echo '<td>'.$row['idCompteV'].'</td>';
                                    echo '<td>'.($row['idCompteA'] ?? 'Aucun').'</td>';
                                    echo '<td>'.($row['dateDerniereEnchere'] ?? 'Aucune').'</td>';
                                    echo '<td>'.($row['dateFinEnchere'] ?? 'Non définie').'</td>';
                                    echo '<td>';
                                    
                                    // Vérifier si l'enchère est terminée et si l'utilisateur connecté est le gagnant
                                    $timezone = new DateTimeZone('Europe/Paris');
                                    $dateEnchere = new DateTime($row['DateEnchere'], $timezone);
                                    $dateFinEnchere = new DateTime($row['dateFinEnchere'], $timezone);
                                    $maintenant = new DateTime('now', $timezone);
                                    
                                    // Calculer la limite de temps pour payer (5 minutes après la fin)
                                    $limitePaiement = clone $dateFinEnchere;
                                    $limitePaiement->modify('+5 minutes');
                                    
                                    // On vérifie que :
                                    // 1. La date d'enchère est passée
                                    // 2. On est avant la limite de paiement
                                    // 3. L'utilisateur est le gagnant
                                    if ($dateEnchere < $maintenant && 
                                        $maintenant < $limitePaiement && 
                                        $row['idCompteA'] === $_SESSION['identifiant'] &&
                                        $dateFinEnchere < $maintenant) {
                                        $urlPaiement = site_url('welcome/contenu/Paiement').'?idLot='.$row['idLot'].'&idBateau='.$row['idBateau'].'&datePeche='.urlencode($row['datePeche']);
                                        echo '<a href="'.$urlPaiement.'" class="btn btn-success">Payer</a>';
                                    }
                                    elseif ($row['idCompteV'] == $_SESSION['identifiant']) {
                                        echo '';
                                    }
                                    elseif ($row['idCompteA'] !== $_SESSION['identifiant']) {
                                        echo 'Vous n\'êtes pas le gagnant de cette enchère';
                                    }
 
                                    elseif ($maintenant < $dateEnchere) {
                                        echo 'L\'enchère n\'a pas encore commencé';
                                    }   
                                    elseif ($maintenant > $limitePaiement) {
                                        echo 'Le délai de paiement est dépassé';
                                    }

                                    // Ajouter le bouton Supprimer si l'utilisateur est le vendeur
                                    if ($row['idCompteV'] === $_SESSION['identifiant']) {
                                        // Si le bouton Supprimer a été cliqué
                                        if(isset($_GET['action']) && $_GET['action'] === 'supprimer' 
                                           && isset($_GET['idLot']) && $_GET['idLot'] === $row['idLot']
                                           && isset($_GET['idBateau']) && $_GET['idBateau'] === $row['idBateau']
                                           && isset($_GET['datePeche']) && $_GET['datePeche'] === $row['datePeche']) {
                                            
                                            // Supprimer l'image si elle existe
                                            if($row['idImage']) {
                                                $cheminImage = $_SERVER['DOCUMENT_ROOT'] . "/codeIgniter318-Criee/assets/" . $row['idImage'];
                                                if(file_exists($cheminImage)) {
                                                    unlink($cheminImage);
                                                }
                                            }

                                            $deleteAnnonce = "DELETE FROM ANNONCE WHERE idLot = :idLot AND idBateau = :idBateau AND datePeche = :datePeche AND idCompteV = :idCompteV";
                                            $stmt = $pdo->prepare($deleteAnnonce);
                                            $stmt->bindParam(':idLot', $row['idLot'], PDO::PARAM_INT);
                                            $stmt->bindParam(':idBateau', $row['idBateau'], PDO::PARAM_STR);
                                            $stmt->bindParam(':datePeche', $row['datePeche'], PDO::PARAM_STR);
                                            $stmt->bindParam(':idCompteV', $_SESSION['identifiant'], PDO::PARAM_STR);
                                            
                                            if($stmt->execute()) {
                                                $_SESSION['message'] = "L'annonce et son image ont été supprimées avec succès.";
                                                echo '<script>window.location.href = "'.site_url('welcome/contenu/Annonces').'";</script>';
                                                exit;
                                            }
                                        }
                                        
                                        echo '<br>';
                                        // Afficher le bouton Supprimer
                                        $urlSuppression = site_url('welcome/contenu/Annonces').'?action=supprimer&idLot='.$row['idLot'].'&idBateau='.$row['idBateau'].'&datePeche='.urlencode($row['datePeche']);
                                        echo '<a onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette annonce ?\')" href="'.$urlSuppression.'" class="btn">Supprimer</a>';
                                    }
                                    echo '</td>';
                                    
                                    // Colonne Infos Paiement
                                    echo '<td style="text-align: left; font-size: 0.8em;">';
                                    if ($row['idCompteA'] === $_SESSION['identifiant']) {
                                        if ($maintenant < $dateFinEnchere) {
                                            echo 'Disponible après la fin de l\'enchère<br><br>';
                                            echo 'Fin de l\'enchère : ' . $dateFinEnchere->format('d/m/Y H:i:s') . '<br>';
                                            echo 'Disponible jusqu\'au : ' . $limitePaiement->format('d/m/Y H:i:s');

                                        } elseif ($maintenant > $limitePaiement) {
                                            echo 'Délai de paiement dépassé<br>';
                                            echo 'Limite dépassée le : ' . $limitePaiement->format('d/m/Y H:i:s');
                                        } else {
                                            echo 'Temps restant pour payer : ';
                                            $tempsRestant = $maintenant->diff($limitePaiement);
                                            echo $tempsRestant->format('%i min %s sec');
                                        }
                                    } else {
                                        if ($maintenant < $dateFinEnchere) {
                                            echo 'Enchère en cours jusqu\'au :<br>' . $dateFinEnchere->format('d/m/Y H:i:s');
                                        } elseif ($maintenant > $limitePaiement) {
                                            echo 'Enchère terminée';
                                        } else {
                                            echo 'En attente de paiement';
                                        }
                                    }
                                    echo '</td>';
                                    
                                    echo '</tr>';
                                }
                                echo '</tbody></table>';
                            } else {
                                echo "<br>
                                Aucune annonce n'est disponible pour le moment.";
                                echo '
                                <form method="POST" action="' . site_url('welcome/contenu/Annonces_Creation') . '">
                                <button type="submit" class="btn">Créer une annonce</button>
                                </form>';
                            }
                        }
                    }
                } else {
                    echo '<br>
                    Veuillez vous connecter pour voir les annonces ou créer une annonce.
                    <form method="POST" action="' . site_url('welcome/contenu/Connexion') . '">
                        <br><button type="submit" class="btn">Connexion</button>
                    </form>';
                }
                $pdo=null;
            ?>
    </section> 
</body>