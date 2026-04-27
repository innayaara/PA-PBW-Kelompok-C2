<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/LiburModel.php';

class LiburController
{
    private $conn;
    private $liburModel;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->liburModel = new LiburModel($this->conn);
    }

    public function index()
    {
        return $this->liburModel->getAllLibur();
    }

    public function store($data)
    {
        if (empty($data['destinasi']) || empty($data['tanggal'])) {
            return false;
        }
        return $this->liburModel->addLibur($data['destinasi'], $data['tanggal'], $data['keterangan'] ?? '');
    }

    public function destroy($id)
    {
        return $this->liburModel->deleteLibur($id);
    }
}
