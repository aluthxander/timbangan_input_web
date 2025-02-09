<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';
$data = array();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action == 'get') {
        $sql = "SELECT nama_barang AS nama, kode_barang AS kode, harga_satuan AS harga, stok_barang AS stok, id, total_dibeli FROM barang;";
        try {
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll();
            // $data = $result ? $result : array();
            if (count($result) > 0) {
                foreach ($result as $key => $row) {
                    $no = $key + 1;
                    $row['no'] = $no;
                    $row['harga'] ="Rp. ". number_format($row['harga'], 0, ',', '.');
                    $row['stok'] = number_format($row['stok'], 0, ',', '.');
                    $data['data'][] = $row;
                }
            }else{
                $data['data'] = array();
            }
        } catch (PDOException $e) {
            $data = array();
            // error_log("Query failed: " . $e->getMessage()); // Log error untuk debugging
            BasicMethod::Logger()->error("Query failed: " . $e->getMessage(), ['file' => __FILE__, 'line' => __LINE__]);
        }
    }elseif($action == 'getid'){
        $kode = isset($_GET['data']) ? BasicMethod::cleanAndFilterKode($_GET['data']) : '';
        $sql = "SELECT kode_barang AS kode, nama_barang AS nama, harga_satuan AS harga, stok_barang AS stok, id FROM barang WHERE kode_barang = :id;";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $kode, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            $data = $result ? $result : array();
        } catch (PDOException $e) {
            $data = array();
            // error_log("Query failed: " . $e->getMessage()); // Log error untuk debugging
            BasicMethod::Logger()->error("Query failed: " . $e->getMessage(), ['file' => __FILE__, 'line' => __LINE__]);
        }
    }else{
        // konten tidak ditemukan
        http_response_code(404);
        exit('404 Not Found');
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    if (BasicMethod::validateCSRFToken($csrf_token)) {
        if($action == 'insert'){
            // // validasi
            $kode = isset($_POST['kodebrg']) ? BasicMethod::cleanAndFilterKode($_POST['kodebrg']) : '';
            $nama = isset($_POST['namabrg']) ? htmlspecialchars($_POST['namabrg'], ENT_QUOTES, 'UTF-8') : '';
            $harga_satuan = isset($_POST['hrgsatuan']) ? BasicMethod::rupiahToFloat($_POST['hrgsatuan']) : 0;
            $stock = isset($_POST['stockbrg']) ? BasicMethod::ribuanToFloat($_POST['stockbrg']) : 0;
            // cek kode barang
            if (empty($kode)) {
                $data = array(
                    "status" => false,
                    "message" => "Kode barang tidak boleh kosong"
                );
            }else{
                $dataForm = [
                    ":kode"=> $kode,
                    ":nama"=> $nama,
                    ":harga"=> $harga_satuan,
                    ":stock"=> $stock,
                ];

                $sql = "INSERT INTO barang (kode_barang, nama_barang, harga_satuan, stok_barang) VALUES (:kode, :nama, :harga, :stock);";
                try {
                    $stmt = $conn->prepare($sql);
            
                    $stmt->execute($dataForm);
                    $data = array(
                        "status" => true,
                        "message" => "Barang berhasil ditambahkan"
                    );
                } catch (PDOException $e) {
                    if ($e->getCode() == '23000') {
                        // Kesalahan duplikasi data
                        $data = array(
                            "status" => false,
                            "message" => $e->getCode()
                        );
                    } else {
                        // Kesalahan lainnya
                        $data = array(
                            "status" => false,
                            "message" => "Gagal menambahkan barang"
                        );
                    }
                    // error_log("Query failed: " . $e->getMessage()); // Log error untuk debugging
                    BasicMethod::Logger()->error("Query failed: " . $e->getMessage(), ['file' => __FILE__, 'line' => __LINE__]);
                }
            }
        }elseif($action == 'update'){
            $kode_awal = isset($_GET['data']) ? BasicMethod::cleanAndFilterKode($_GET['data']) : '';
            // validasi data
            $kode = isset($_POST['kodebrg']) ? BasicMethod::cleanAndFilterKode($_POST['kodebrg']) : '';
            $nama = isset($_POST['namabrg']) ? htmlspecialchars($_POST['namabrg'], ENT_QUOTES, 'UTF-8') : '';
            $harga_satuan = isset($_POST['hrgsatuan']) ? BasicMethod::rupiahToFloat($_POST['hrgsatuan']) : 0;
            $stock = isset($_POST['stockbrg']) ? BasicMethod::ribuanToFloat($_POST['stockbrg']) : 0;

            $dataForm = [
                ":kode"=> $kode,
                ":nama"=> $nama,
                ":harga"=> $harga_satuan,
                ":stock"=> $stock,
                ":kode_awal"=> $kode_awal
            ];
            
            $sql = "UPDATE barang SET kode_barang = :kode, nama_barang = :nama, harga_satuan = :harga, stok_barang = :stock WHERE kode_barang = :kode_awal;";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute($dataForm);
                $data = array(
                    "status" => true,
                    "message" => "Barang berhasil diupdate"
                );
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    // Kesalahan duplikasi data
                    $data = array(
                        "status" => false,
                        "message" => $e->getCode()
                    );
                } else {
                    // Kesalahan lainnya
                    $data = array(
                        "status" => false,
                        "message" => "Gagal mengupdate barang"
                    );
                }
                BasicMethod::Logger()->error("Query failed: " . $e->getMessage(), ['file' => __FILE__, 'line' => __LINE__]);
            }
        }elseif($action == 'delete'){
            $id = isset($_POST['data']) ? BasicMethod::cleanAndFilterKode($_POST['data']) : '';
            $sql = "DELETE FROM barang WHERE kode_barang = :id;";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                $stmt->execute();
                $data = array(
                    "status" => true,
                    "message" => "Barang berhasil dihapus"
                );
            } catch (PDOException $e) {
                $data = array(
                    "status" => false,
                    "message" => "Gagal menghapus barang"
                );
                BasicMethod::Logger()->error("Query failed: " . $e->getMessage(), ['file' => __FILE__, 'line' => __LINE__]);
            }
        }else{
            http_response_code(400);
            exit('400 Bad Request');
        }
    }else{
        http_response_code(403);
        exit('403 Forbidden');
    }
}

echo json_encode($data);
exit;
?>