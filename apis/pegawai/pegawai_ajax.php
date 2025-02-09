<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';
$data = array();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action == 'get') {
        $sql = "SELECT nip, nama, email, jabatan, id FROM pegawai;";
        try {
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll();
            // $data = $result ? $result : array();
            if (count($result) > 0) {
                foreach ($result as $key => $row) {
                    $no = $key + 1;
                    $row['no'] = $no;
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
        $id = isset($_GET['data']) ? BasicMethod::cleanAndConvertToNumber($_GET['data']) : '';
        $sql = "SELECT nip, nama, email, jabatan, id FROM pegawai WHERE id = :id;";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
            // validasi
            $nip = isset($_POST['nip']) ? BasicMethod::cleanNumString($_POST['nip']) : '';
            $nama = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : '';
            $email = isset($_POST['email']) ? BasicMethod::cleanAndValidateEmail($_POST['email']) : '';
            $jabatan = isset($_POST['jabatan']) ? BasicMethod::cleanAlphanumeric($_POST['jabatan']) : '';
            $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';
            // cek email dan password
            if ($email == false) {
                $data = array(
                    "status" => false,
                    "message" => "Email tidak valid"
                );
            }else if($_POST['password'] == ''){
                $data = array(
                    "status" => false,
                    "message" => "Password harus diisi"
                );
            }else{
                $data = [
                    ":nip"=> $nip,
                    ":nama"=> $nama,
                    ":email"=> $email,
                    ":jabatan"=> $jabatan,
                    ":pass"=> $password
                ];
                $sql = "INSERT INTO pegawai (nip, nama, email, jabatan, `password`) VALUES (:nip, :nama, :email, :jabatan, :pass);";
                try {
                    $stmt = $conn->prepare($sql);
            
                    $stmt->execute($data);
                    $data = array(
                        "status" => true,
                        "message" => "Pegawai berhasil ditambahkan"
                    );
                } catch (PDOException $e) {
                    if ($e->getCode() == '23000') {
                        // Kesalahan duplikasi data
                        $data = array(
                            "status" => false,
                            "message" => "Gagal menambahkan pegawai: NIP sudah digunakan."
                        );
                    } else {
                        // Kesalahan lainnya
                        $data = array(
                            "status" => false,
                            "message" => "Gagal menambahkan pegawai"
                        );
                    }
                    // error_log("Query failed: " . $e->getMessage()); // Log error untuk debugging
                    BasicMethod::Logger()->error("Query failed: " . $e->getMessage(), ['file' => __FILE__, 'line' => __LINE__]);
                }
            }
        }elseif($action == 'update'){
            $id = isset($_GET['data']) ? BasicMethod::cleanAndConvertToNumber($_GET['data']) : '';
            // validasi data
            $name = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : '';
            $email = isset($_POST['email']) ? BasicMethod::cleanAndValidateEmail($_POST['email']) : '';
            $jabatan = isset($_POST['jabatan']) ? BasicMethod::cleanAlphanumeric($_POST['jabatan']) : '';
            $nip = isset($_POST['nip']) ? BasicMethod::cleanNumString($_POST['nip']) : '';
            
            $data = [
                ":id"=> $id,
                ":name"=> $name,
                ":email"=> $email,
                ":jabatan"=> $jabatan,
                ":nip"=> $nip
            ];
            $sql = "UPDATE pegawai SET nama = :name, email = :email, jabatan = :jabatan, nip = :nip WHERE id = :id;";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute($data);
                $data = array(
                    "status" => true,
                    "message" => "Pegawai berhasil diupdate"
                );
            } catch (PDOException $e) {
                $data = array(
                    "status" => false,
                    "message" => "Gagal mengupdate pegawai"
                );
                BasicMethod::Logger()->error("Query failed: " . $e->getMessage(), ['file' => __FILE__, 'line' => __LINE__]);
            }
        }elseif($action == 'delete'){
            $id = isset($_POST['data']) ? BasicMethod::cleanAndConvertToNumber($_POST['data']) : '';
            $sql = "DELETE FROM pegawai WHERE id = :id;";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $data = array(
                    "status" => true,
                    "message" => "Pegawai berhasil dihapus"
                );
            } catch (PDOException $e) {
                $data = array(
                    "status" => false,
                    "message" => "Gagal menghapus pegawai"
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