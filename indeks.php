<?php
    //Array daftar bandara dan pajak asal
	$bandaraAsal = array ("Soekarno-Hatta (CGK)", 
                          "Husein Sastranegara (BDO)", 
                          "Abdul Rachman Saleh (MLG)", 
                          "Juanda (SUB)");
                          
    $pajakAsal = array ("Soekarno-Hatta (CGK)" => 50000, 
                        "Husein Sastranegara (BDO)" => 30000, 
                        "Abdul Rachman Saleh (MLG)" => 40000, 
                        "Juanda (SUB)" => 40000);

    //Array daftar bandara dan pajak tujuan
	$bandaraTujuan = array ("Ngurah Rai (DPS)", 
                            "Hasanuddin (UPG)", 
                            "Inanwatan (INX)", 
                            "Sultan Iskandarmuda (BTJ)");

	$pajakTujuan = array ("Ngurah Rai (DPS)" => 80000, 
                          "Hasanuddin (UPG)" => 70000, 
                          "Inanwatan (INX)" => 90000, 
                          "Sultan Iskandarmuda (BTJ)" => 70000);

	$data = "data/data.json"; //letak file json
	$getData = file_get_contents($data); //akses file json
	$ruteTerbang = json_decode($getData, true); //konversi file json ke php

	//Fungsi Menghitung Total Pajak Bandara
	function totalPajak($pajakAsalB, $pajakTujuanB){
		global $pajakAsal, $pajakTujuan;

        //mangambil data biaya pajak dari bandara asal
		foreach ($pajakAsal as $pajak1 =>$value) {
			if($pajakAsalB == $pajak1){
				$PajakA = $value;
			}
		}

        //mangambil data biaya pajak dari bandara tujuan
		foreach ($pajakTujuan as $pajak2 =>$value2) {	
			if($pajakTujuanB == $pajak2){
				$PajakB = $value2;
			}
		}

		return $PajakA + $PajakB;
	}

    //menghitung total harga maskapai setelah dikenai pajak
	function totalHarga($totalPajak, $hargaTiket){
		return $totalPajak + $hargaTiket;
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Jadwal Penerbangan</title>
        <!-- css -->
        <link rel="stylesheet" href="css/style.css">
        <!-- bootstrap -->
        <link rel="stylesheet" href="library/Bootstrap/bootstrap/css/bootstrap.min.css" />
    </head>
    <body>
        <header>
        </header>
        <section>
            <article>
                <div class="container">
                    <h1> Jadwal Penerbangan Maskapai </h1>
                    <form action="" method="post">
                        <fieldset class="form-group border p-4">
                            <div class="form-group">
                                <label for="nama">Nama Maskapai:</label>
                                <input type="text" class="form-control" name="maskapai" placeholder="Nama Maskapai" required="">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="jk">Bandara Asal:</label>
                                <select class="form-select" id="jk" name="ruteasal">
                                    <?php
                                        //mengambil data nama bandara asal
                                        foreach ($bandaraAsal as $asal) { 
                                            echo "<option value='".$asal."'>".$asal."</option>"; 
                                        }
                                    ?>
                                </select>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="jk">Bandara Tujuan:</label>
                                <select  class="form-select" id="jk" name="rutetujuan">
                                    <?php
                                        //mengambil data nama bandara tujuan
                                        foreach ($bandaraTujuan as $tujuan) {
                                            echo "<option value='".$tujuan."'>".$tujuan."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="nama">Harga Tiket:</label>
                                <input type="number" class="form-control" name="harga" placeholder="Harga Tiket" required="">
                            </div>
                            <br>
                            <br>
                            <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                            <button type="reset" class="btn btn-primary m-3" name="reset">Reset</button>
                        </fieldset>
                    </form>
                </div>
                <br>
                <br>
                <br>

                <!-- menampung hasil inputan user -->
                <?php
                    if(isset($_POST['submit'])){
                        $maskapai = $_POST['maskapai'];
                        $ruteAsal = $_POST['ruteasal'];
                        $ruteTujuan = $_POST['rutetujuan'];
                        $hargaTiket = $_POST['harga'];
                        $totalPajak = totalPajak($ruteAsal, $ruteTujuan);
                        $totalHarga = totalHarga($totalPajak, $hargaTiket);

                        
                        $rutePenerbangan = [$maskapai, $ruteAsal, $ruteTujuan, $hargaTiket, $totalPajak, $totalHarga];//Menampung inputan User
                        array_push($ruteTerbang, $rutePenerbangan);	//Memasukan Array baru kedalam Array baru
                        array_multisort($ruteTerbang, SORT_ASC);  //Mengurutkan Nama Maskapai sesuai Abjad
                        $dataJson = json_encode($ruteTerbang, JSON_PRETTY_PRINT); //mengubah format data array menjadi json
                        file_put_contents($data, $dataJson); //menuliskan teks ke file json
                    }

                ?>
                
                <!-- menampilkan array dari inputan user dalam bentuk tabel -->
                <h1> Data Rute Penerbangan </h1>
                <div class="container">
                    <fieldset class="form-group border p-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Maskapai</th>
                                    <th>Asal Penerbangan</th>
                                    <th>Tujuan Penerbangan</th>
                                    <th>Harga Tiket</th>
                                    <th>Pajak</th>
                                    <th>Total Harga Tiket</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for($i = 0; $i < count($ruteTerbang); $i++){
                                        echo "<tr>";
                                        echo "<td>".$ruteTerbang[$i][0]."</td>";
                                        echo "<td>".$ruteTerbang[$i][1]."</td>";
                                        echo "<td>".$ruteTerbang[$i][2]."</td>";
                                        echo "<td>".$ruteTerbang[$i][3]."</td>";
                                        echo "<td>".$ruteTerbang[$i][4]."</td>";
                                        echo "<td>".$ruteTerbang[$i][5]."</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div>
            </article>
        </section>
        <script src="library/Bootstrap/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>