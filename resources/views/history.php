<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <title>Test History</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <form class="form-inline">
            <div class="form-group mb-2">
                <label for="blokId" class="sr-only">Blok ID</label>

                <select class="form-control" name="blokId" id="blokId">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Filter</button>
        </form>
    </div>
        <div class="table-responsive">
            <table class="table" id="content">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Blok</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Va</th>
                        <th>Vb</th>
                        <th>Vc</th>
                        <th>Vab</th>
                        <th>Vbc</th>
                        <th>Vca</th>
                        <th>Ia</th>
                        <th>Ib</th>
                        <th>Ic</th>
                        <th>Pa</th>
                        <th>Pb</th>
                        <th>Pc</th>
                        <th>Pt</th>
                        <th>Pfa</th>
                        <th>Pfb</th>
                        <th>Pfc</th>
                        <th>Ep</th>
                        <th>Eq</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
foreach ($data as $value) {
    echo "<tr>";
    echo '<td>' . $value['blok_id'] . '</td>';
    echo '<td>' . $value['tanggal'] . '</td>';
    echo '<td>' . $value['waktu'] . '</td>';
    echo '<td>' . $value['va'] . '</td>';
    echo '<td>' . $value['vb'] . '</td>';
    echo '<td>' . $value['vc'] . '</td>';
    echo '<td>' . $value['vab'] . '</td>';
    echo '<td>' . $value['vbc'] . '</td>';
    echo '<td>' . $value['vca'] . '</td>';
    echo '<td>' . $value['ia'] . '</td>';
    echo '<td>' . $value['ib'] . '</td>';
    echo '<td>' . $value['ic'] . '</td>';
    echo '<td>' . $value['pa'] . '</td>';
    echo '<td>' . $value['pb'] . '</td>';
    echo '<td>' . $value['pc'] . '</td>';
    echo '<td>' . $value['pt'] . '</td>';
    echo '<td>' . $value['pfa'] . '</td>';
    echo '<td>' . $value['pfb'] . '</td>';
    echo '<td>' . $value['pfc'] . '</td>';
    echo '<td>' . $value['ep'] . '</td>';
    echo '<td>' . $value['eq'] . '</td>';
    echo "</tr>";
}
?>
                </tbody>
            </table>
        </div>

    <?php echo $data->links() ?>


</body>
</html>