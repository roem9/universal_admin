<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1><?= $title?></h1>
    <table border=1 style="border-collapse: collapse">
        <thead>
            <tr>
                <th rowspan="2" style="padding: 10px">No</th>
                <th rowspan="2" style="padding: 10px">Nama Lengkap</th>
                <th rowspan="2" style="padding: 10px">TTL</th>
                <th rowspan="2" style="padding: 10px">Alamat</th>
                <th rowspan="2" style="padding: 10px">Alamat Pengiriman</th>
                <th rowspan="2" style="padding: 10px">No Whatsapp</th>
                <th rowspan="2" style="padding: 10px">Email</th>
                <th colspan="2" style="padding: 10px">Nilai Listening</th>
                <th colspan="2" style="padding: 10px">Nilai Structure</th>
                <th colspan="2" style="padding: 10px">Nilai Reading</th>
                <th rowspan="2" style="padding: 10px">SKOR</th>
                <th rowspan="2" style="padding: 10px">SKOR</th>
            </tr>
            <tr>
                <th style="padding: 10px">Jumlah Benar</th>
                <th style="padding: 10px">Score</th>
                <th style="padding: 10px">Jumlah Benar</th>
                <th style="padding: 10px">Score</th>
                <th style="padding: 10px">Jumlah Benar</th>
                <th style="padding: 10px">Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($respon as $i => $respon) :?>
                <?php $skor = skor($respon['nilai_listening'], $respon['nilai_structure'], $respon['nilai_reading']);?>
                <tr>
                    <td style="padding: 10px"><?= $i+1?></td>
                    <td style="padding: 10px"><?= ucwords(strtolower($respon['nama']))?></td>
                    <td style="padding: 10px"><?= ucwords(strtolower($respon['t4_lahir'])) . ", " . tgl_indo($respon['tgl_lahir'])?></td>
                    <td style="padding: 10px"><?= ucwords(strtolower($respon['alamat']))?></td>
                    <td style="padding: 10px"><?= ucwords(strtolower($respon['alamat_pengiriman']))?></td>
                    <td style="padding: 10px"><?= $respon['no_wa']?></td>
                    <td style="padding: 10px"><?= $respon['email']?></td>
                    
                    <td style="padding: 10px"><center><?= $respon['nilai_listening']?></center></td>
                    <td style="padding: 10px"><center><?= poin("Listening", $respon['nilai_listening'])?></center></td>
                    <td style="padding: 10px"><center><?= $respon['nilai_structure']?></center></td>
                    <td style="padding: 10px"><center><?= poin("Structure", $respon['nilai_structure'])?></center></td>
                    <td style="padding: 10px"><center><?= $respon['nilai_reading']?></center></td>
                    <td style="padding: 10px"><center><?= poin("Reading", $respon['nilai_reading'])?></center></td>
                    <td style="padding: 10px"><center><?= round($skor)?></center></td>
                    <td style="padding: 10px"><center><?= $skor?></center></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</body>
</html>