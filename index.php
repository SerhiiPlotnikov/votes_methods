<?php
/**
 * Created by PhpStorm.
 * User: Serhii
 * Date: 26.04.2018
 * Time: 0:12
 */

declare(strict_types = 1);
include "vendor/autoload.php";

//Чтение профиля из файла
$data = file('profile.txt');
$profile = [];
foreach ($data as $string) {
    $arr = preg_split('#=>#', $string);
    array_walk($arr, function (string &$elem) {
        $elem = trim($elem);
    });
    list($count, $candidates) = $arr;
    $profile[][$count] = explode(',', $candidates);
}

//Данные профиля
$tableData = [];
for ($i = 0; $i < count($profile); $i++) {
    foreach ($profile[$i] as $candidates) {
        for ($j = 0; $j < count($candidates); $j++) {
            $tableData[$j][$i] = $candidates[$j];
        }
    }
}

$voting = new VotingMethods(new RelativeMajority($profile));
$relativeMajorityRanking = $voting->calculateCollectiveRanking();
$voting->setVotingMethod(new Condorcet($profile));
$condorcetRanking = $voting->calculateCollectiveRanking();
$voting->setVotingMethod(new AlternativeVoting($profile));
$alternativeVotingRanking = $voting->calculateCollectiveRanking();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voting Methods</title>
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <p class="h3 text-center">Профиль голосования</p>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <?php
                    for ($i = 0; $i < count($profile); $i++):
                        foreach ($profile[$i] as $count => $candidates) :
                            ?>
                            <th><?= $count ?></th>
                            <?php
                        endforeach;
                    endfor;
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php for ($i = 0; $i < count($tableData); $i++): ?>
                    <tr>
                        <?php
                        for ($j = 0; $j < count($tableData[$i]); $j++) :?>
                            <td><?= $tableData[$i][$j] ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <p class="h3 text-center"> Метод относительного большинства </p>
            <div class="row">
                <div class="col-sm-6">
                    <div class="alert alert-primary"> Коллективное ранжирование</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th> Кандидат</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($relativeMajorityRanking as $place => $candidate):
                            ?>
                            <tr>
                                <td><?= ++$place ?></td>
                                <td><?= $candidate ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <p class="h3 text-center">Метод Кондорсе</p>
            <div class="row">
                <div class="col-sm-6">
                    <div class="alert alert-primary">Коллективное ранжирование</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Кандидат</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($condorcetRanking as $place => $candidate):
                            ?>
                            <tr>
                                <td><?= ++$place ?></td>
                                <td><?= $candidate ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <p class="h3 text-center">Метод альтернативных голосов</p>
            <div class="row">
                <div class="col-sm-6">
                    <div class="alert alert-primary">Коллективное ранжирование</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Кандидат</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($alternativeVotingRanking as $place => $candidate):
                            ?>
                            <tr>
                                <td><?= ++$place ?></td>
                                <td><?= $candidate ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
