<?php
    function day($n) {
        $n = intval($n);
        if ($n >= 11 && $n <= 13) {
            return "{$n}<sup>th</sup>";
        }
        switch ($n % 10) {
            case 1:  return "{$n}<sup>st</sup>";
            case 2:  return "{$n}<sup>nd</sup>";
            case 3:  return "{$n}<sup>rd</sup>";
            default: return "{$n}<sup>th</sup>";
        }
    }

    function tgl_sertifikat($tgl){
        $data = explode("-", $tgl);
        $hari = day($data[0]);
        $bulan = $data[1];
        $tahun = $data[2];

        if($bulan == "01") $bulan = "January";
        if($bulan == "02") $bulan = "February";
        if($bulan == "03") $bulan = "March";
        if($bulan == "04") $bulan = "April";
        if($bulan == "05") $bulan = "May";
        if($bulan == "06") $bulan = "June";
        if($bulan == "07") $bulan = "July";
        if($bulan == "08") $bulan = "August";
        if($bulan == "09") $bulan = "September";
        if($bulan == "10") $bulan = "October";
        if($bulan == "11") $bulan = "November";
        if($bulan == "12") $bulan = "December";

        return $hari . " of " . $bulan . " " . $tahun;
    }
?>

<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .qrcode{
            width: 210px;
			position: absolute;
            /*left: 550px;*/
            /*left: 421px;*/
            left: 433px;
			/*bottom: 160px;*/
			/*bottom: 152px;*/
			bottom: 88px;
            font-size: 35px;
            word-spacing: 3px;
        }

        .nilai{
            /*background-color: gray;*/
            width: 95px;
			position: absolute;
            /*right: 212px;*/
            /*right: 341px;*/
            right: 329px;
			/*bottom: 174px; -8 */ 
			/*bottom: 166px; +2*/
			bottom: 168px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .nama{
            /*background-color: gray;*/
            width: 700px;
			position: absolute;
            /*left: 330px;*/
            /*left: 201px;*/
            /*left: 213px; -5*/
            left: 208px;
			<?php if(strlen($nama) > 60) :?>
			    top: 275px;
                font-size: 25px;
            <?php elseif(strlen($nama) > 30) :?>
                top: 300px;
                font-size: 25px;
            <?php else :?>
			    top: 300px;
                font-size: 32px;
            <?php endif;?>
            font-family: 'rockb';
            word-spacing: 3px;
        }

        .ttl{
            /*background-color: gray;*/
            width: 129px;
			position: absolute;
            /*right: 413px;*/
            /*right: 542px;*/
            right: 530px;
			/*top: 355px;*/
			/*top: 363px; -3*/
			top: 360px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .t4{
            /* background-color: red; */
            <?php if(strlen($t4_lahir) < 12 ) echo 'width: 129px;';?>
			position: absolute;
            /*right: 229px;*/
            /*right: 358px;*/
            /* right: 346px; -- */
            left: 772px;
			/*top: 355px;*/
			/*top: 363px; -3*/
			top: 360px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }
        
        .istima{
            /*background-color: gray;*/
            width: 95px;
			position: absolute;
            /*right: 212px;*/
            /*right: 341px;*/
            right: 329px;
			/*bottom: 247px;*/
			/*bottom: 239px; +3*/
			bottom: 242px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }
        
        .tarakib{
            /*background-color: gray;*/
            width: 95px;
			position: absolute;
            /*right: 212px;*/
            /*right: 341px;*/
            right: 329px;
			/*bottom: 222px;*/
			/*bottom: 214px; +3*/
			bottom: 217px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }
        
        .qiroah{
            /*background-color: gray;*/
            width: 95px;
			position: absolute;
            /*right: 212px;*/
            /*right: 341px;*/
            right: 329px;
			/*bottom: 197px;*/
			/*bottom: 189px; +3*/
			bottom: 192px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .tgl{
            /*background-color: gray;*/
			position: absolute;
            /*left: 797px;*/
            /*left: 668px;*/
            left: 680px;
			/*bottom: 126px;*/
			/*bottom: 118px; +2*/
			bottom: 120px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .no_doc{
            /*background-color: gray;*/
            width: 129px;
			position: absolute;
            /*left: 464px;*/
            /*left: 335px;*/
            left: 347px;
			/*top: 355px;*/ 
			/*top: 363px; -3*/
			/* top: 360px; */
			top: 364px;
            /* font-size: 18px; */
            font-size: 16px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .gender{
            /*background-color: gray;*/
            width: 129px;
			position: absolute;
            /*left: 373px;*/
            /*left: 244px;*/
            left: 256px;
			/*top: 407px;*/
			/*top: 415px; -3*/
			top: 412px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .country{
            /*background-color: gray;*/
            width: 129px;
			position: absolute;
            /*left: 631px;*/
            /*left: 502px;*/
            left: 514px;
			/*top: 407px;*/
			/*top: 415px; -3*/
			top: 412px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .language{
            /*background-color: gray;*/
            width: 129px;
			position: absolute;
            /*right: 210px;*/
            /*right: 339px;*/
            right: 327px;
			/*top: 407px;*/
			/*top: 415px; -3*/
			top: 412px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        .tgl_akhir{
            /*background-color: gray;*/
			position: absolute;
            /*left: 797px;*/
            /*left: 668px;*/
            left: 680px;
			/*bottom: 100px;*/
			/*bottom: 92px; +2*/
			bottom: 94px;
            font-size: 18px;
            font-family: 'rock';
            word-spacing: 3px;
        }

        <?php if($tipe == "gambar") :?>
            @page :first {
                background-image: url("<?= base_url()?>assets/img/sertifikat.jpg");
                background-image-resize: 6;
            }
        <?php endif;?>
        
    </style>
</head>
    <body style="text-align: center">
        <div class="qrcode">
            <img src="<?= base_url()?>assets/qrcode/<?= $id?>.png" width=100 alt="">
        </div>
        <div class="nilai"><p style="text-align: center; margin: 0px"><?= round($skor)?></p></div>
        <div class="nama"><p style="text-align: center; margin: 0px"><?= $nama?></p></div>
        <div class="ttl"><p style="text-align: center; margin: 0px"><?= date("M d Y", strtotime($tgl_lahir))?></p></div>
        <div class="t4"><p style="text-align: center; margin: 0px"><?= $t4_lahir?></p></div>
        <div class="gender"><p style="text-align: center; margin: 0px"><?= $jk?></p></div>
        <div class="country"><p style="text-align: center; margin: 0px"><?= $country?></p></div>
        <div class="language"><p style="text-align: center; margin: 0px"><?= $language?></p></div>
        <div class="istima"><p style="text-align: center; margin: 0px"><?= $istima?></p></div>
        <div class="tarakib"><p style="text-align: center; margin: 0px"><?= $tarakib?></p></div>
        <div class="qiroah"><p style="text-align: center; margin: 0px"><?= $qiroah?></p></div>
        <div class="no_doc"><p style="text-align: center; margin: 0px"><?= $no_doc?></p></div>
        <div class="tgl"><p style="text-align: center; margin: 0px"><?= tgl_sertifikat(date("d-m-Y", strtotime($tgl_tes)))?></p></div>
        <div class="tgl_akhir"><p style="text-align: center; margin: 0px"><?= tgl_sertifikat(date("d-m-Y", strtotime('+2 years', strtotime($tgl_tes))))?></p></div>
        
        <?php if($tipe == "gambar") :?>
            <pagebreak>
            <div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
                <img src="<?= base_url()?>assets/img/sertifikat2.jpg" 
                    style="width: 210mm; height: 330mm; margin: 0;" />
            </div>
        <?php endif;?>
    </body>
</html>