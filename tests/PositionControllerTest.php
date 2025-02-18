<?php

use PHPUnit\Framework\TestCase;

use Ltech\WebTimbangan\controllers\PositionController;


class PositionControllerTest extends TestCase {
    public function testAccesPosition() {
        $controller = new PositionController();
        ob_start();
        $controller->accesPosition();
        $result = ob_get_clean();
        // var_dump($result);
        // Pastikan hasilnya adalah string JSON yang valid
        $this->assertJson($result, "Output tidak berupa JSON yang valid.");

        // // Ubah JSON menjadi array untuk pengujian lebih lanjut
        // $data = json_decode($result, true);
        // // Pastikan data tidak kosong setelah decode
        // $this->assertNotNull($data, "JSON tidak bisa didecode.");
        
        // Periksa apakah struktur JSON sesuai
        // $this->assertArrayHasKey('status', $data);
        // $this->assertArrayHasKey('data', $data);
        // $this->assertArrayHasKey('read', $data[0]['data']);
        // $this->assertArrayHasKey('create', $data[0]['data']);
        // $this->assertArrayHasKey('update', $data[0]['data']);
        // $this->assertArrayHasKey('delete', $data[0]['data']);
    }
}