<?php
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 14/12/2018
 * Time: 01:03
 */



include("config.php");
//error_log("id:" . $_POST['id'] . "--------- colonne : " . $_POST['colonne'] . "--------val:" . $_POST['valeur']);
$data = [];

try {
    if (isset($_POST['type'])) {
        $sql = "SELECT *, DATE_FORMAT(date_debut,'%d/%m/%Y') AS date_debut, DATE_FORMAT(date_fin,'%d/%m/%Y') AS date_fin FROM {$_POST['type']} WHERE id=? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['id']]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['msg'] = "ok";
        error_log($sql);

        $emprunts = $pdo->prepare("SELECT DATE_FORMAT(date_debut,'%d/%m/%Y') AS date_debut, DATE_FORMAT(date_fin,'%d/%m/%Y') AS date_fin  FROM emprunt where id_bien_service = ?");
        $emprunts->execute([$_POST['id']]);

        $datesInvalides = [];

        foreach ($emprunts as $emprunt) {
            $startDate = DateTime::createFromFormat("d/m/Y", $emprunt['date_debut'], new DateTimeZone("Europe/Paris"));
            $endDate = DateTime::createFromFormat("d/m/Y", $emprunt['date_fin'], new DateTimeZone("Europe/Paris"));

            $periodInterval = new DateInterval("P1D"); // 1-day, though can be more sophisticated rule
            $period = new DatePeriod($startDate, $periodInterval, $endDate);

            foreach ($period as $date) {
                array_push($datesInvalides, $date->format("d/m/Y"));
            }

            array_push($datesInvalides, $emprunt['date_debut']);
            array_push($datesInvalides, $emprunt['date_fin']);
        }

        $data['datesInvalides'] = $datesInvalides;

        $sql = "SELECT count(*) as compte FROM emprunt where id_bien_service = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['id']]);

        $res = $stmt->fetch();

        $data['emprunte'] = $res['compte'];
    } else {
        $data['msg'] = "erreur";
        $data['erreur'] = "erreur";
    }
    header('Content-Type: application/json');
    echo json_encode($data);
}
catch (PDOException $e)
{
    echo "erreur";
}